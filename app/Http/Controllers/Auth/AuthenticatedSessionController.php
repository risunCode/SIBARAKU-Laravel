<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman auth (login/register).
     */
    public function index(Request $request): Response
    {
        $mode = 'login';
        $referralCode = $request->query('ref', '');
        
        if ($referralCode) {
            $mode = 'register';
        }
        
        // Prevent browser caching to ensure fresh CSRF tokens
        return response()
            ->view('auth.index', compact('mode', 'referralCode'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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

            // Initialize session activity tracking
            $request->session()->put('last_activity', time());
            
            // Force save the new session to database before deleting old ones
            $request->session()->save();

            // Logout dari device lain (double login protection - custom implementation)
            $currentSessionId = session()->getId();
            $userId = Auth::id();
            
            // Delete all other sessions for this user
            $deletedCount = DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $currentSessionId)
                ->delete();
                
            
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
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'description' => 'Login berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Update last login timestamp
            Auth::user()->update(['last_login_at' => now()]);

            // Check if user needs to setup security question
            $user = Auth::user();
            if (!$user->security_setup_completed) {
                return redirect()->route('security.setup')
                    ->with('warning', 'Anda harus mengatur pertanyaan keamanan sebelum dapat menggunakan sistem.');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil! Sesi login dari perangkat lain telah ditutup untuk keamanan akun Anda.');
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
        // Log logout
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'description' => 'Logout berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
