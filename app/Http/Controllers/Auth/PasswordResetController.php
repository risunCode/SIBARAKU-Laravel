<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan form input email.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Verifikasi email dan generate token.
     */
    public function verifyEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Cek apakah user punya security questions
        if (!$user->hasSecurityQuestions()) {
            return back()->withErrors([
                'email' => 'Akun ini belum mengatur pertanyaan keamanan. Hubungi administrator.',
            ]);
        }

        // Generate token dan simpan di session
        $token = Str::random(64);
        session(['password_reset' => [
            'token' => $token,
            'email' => $user->email,
            'expires_at' => now()->addMinutes(config('security_questions.reset_timeout', 15)),
        ]]);

        return redirect()->route('password.security', $token);
    }

    /**
     * Tampilkan form pertanyaan keamanan.
     */
    public function showSecurityQuestions(string $token): View|RedirectResponse
    {
        $reset = session('password_reset');

        if (!$reset || $reset['token'] !== $token || now()->isAfter($reset['expires_at'])) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }

        $user = User::where('email', $reset['email'])->first();

        return view('auth.security-questions', [
            'token' => $token,
            'question1' => $user->security_question_1_text,
            'question2' => $user->security_question_2_text,
        ]);
    }

    /**
     * Verifikasi jawaban pertanyaan keamanan.
     */
    public function verifySecurityQuestions(Request $request, string $token): RedirectResponse
    {
        $reset = session('password_reset');

        if (!$reset || $reset['token'] !== $token || now()->isAfter($reset['expires_at'])) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }

        $request->validate([
            'answer1' => ['required', 'string'],
            'answer2' => ['required', 'string'],
        ]);

        $user = User::where('email', $reset['email'])->first();

        // Verifikasi jawaban
        $answer1Valid = Hash::check(strtolower(trim($request->answer1)), $user->security_answer_1);
        $answer2Valid = Hash::check(strtolower(trim($request->answer2)), $user->security_answer_2);

        if (!$answer1Valid || !$answer2Valid) {
            return back()->withErrors([
                'answer1' => 'Jawaban tidak sesuai. Silakan coba lagi.',
            ]);
        }

        // Update session untuk allow reset
        session(['password_reset.verified' => true]);

        return redirect()->route('password.reset', $token);
    }

    /**
     * Tampilkan form reset password.
     */
    public function showResetForm(string $token): View|RedirectResponse
    {
        $reset = session('password_reset');

        if (!$reset || $reset['token'] !== $token || !($reset['verified'] ?? false) || now()->isAfter($reset['expires_at'])) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi reset password tidak valid. Silakan ulangi.']);
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password.
     */
    public function reset(Request $request): RedirectResponse
    {
        $reset = session('password_reset');

        if (!$reset || !($reset['verified'] ?? false) || now()->isAfter($reset['expires_at'])) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi reset password tidak valid. Silakan ulangi.']);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $reset['email'])->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus session reset
        session()->forget('password_reset');

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
