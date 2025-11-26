<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit(): View
    {
        return view('auth.profile', [
            'user' => Auth::user(),
            'securityQuestions' => config('security_questions.questions'),
        ]);
    }

    /**
     * Update profil user.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Update pertanyaan keamanan.
     */
    public function updateSecurity(Request $request): RedirectResponse
    {
        $questions = config('security_questions.questions');

        $validated = $request->validate([
            'security_question_1' => ['required', 'integer', 'in:' . implode(',', array_keys($questions))],
            'security_answer_1' => ['required', 'string', 'max:255'],
            'security_question_2' => ['required', 'integer', 'in:' . implode(',', array_keys($questions)), 'different:security_question_1'],
            'security_answer_2' => ['required', 'string', 'max:255'],
        ], [
            'security_question_2.different' => 'Pertanyaan kedua harus berbeda dengan pertanyaan pertama.',
        ]);

        Auth::user()->update([
            'security_question_1' => $validated['security_question_1'],
            'security_answer_1' => Hash::make(strtolower(trim($validated['security_answer_1']))),
            'security_question_2' => $validated['security_question_2'],
            'security_answer_2' => Hash::make(strtolower(trim($validated['security_answer_2']))),
        ]);

        return back()->with('success', 'Pertanyaan keamanan berhasil diperbarui.');
    }
}
