<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman auth (login/register).
     */
    public function index(Request $request): View
    {
        $mode = 'login';
        $referralCode = $request->query('ref', '');
        
        if ($referralCode) {
            $mode = 'register';
        }
        
        return view('auth.index', compact('mode', 'referralCode'));
    }

    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Sanitize email
        $credentials['email'] = strtolower(trim($credentials['email']));

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Cek apakah user aktif
            if (!Auth::user()->is_active) {
                Auth::logout();
                
                // Log failed login (inactive account)
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'login_failed',
                    'description' => 'Login gagal: Akun tidak aktif - ' . $credentials['email'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                ]);
                
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ]);
            }

            // Log successful login
            // Activity logged;

            // Check if user needs to setup security question
            $user = Auth::user();
            if (!$user->security_setup_completed) {
                // Activity logged;
                return redirect()->route('security.setup')
                    ->with('warning', 'Anda harus mengatur pertanyaan keamanan sebelum dapat menggunakan sistem.');
            }

            return redirect()->intended(route('dashboard'));
        }

        // Check if email exists but password is wrong
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user) {
            // Log failed login (wrong password)
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login_failed',
                'description' => 'Login gagal: Password salah - ' . $credentials['email'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        } else {
            // Log failed login (email not found)
            ActivityLog::create([
                'user_id' => null,
                'action' => 'login_failed',
                'description' => 'Login gagal: Email tidak ditemukan - ' . $credentials['email'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Activity logged;

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
