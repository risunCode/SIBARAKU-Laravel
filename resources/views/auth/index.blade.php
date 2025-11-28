<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-panel {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .panel-hidden {
            opacity: 0;
            pointer-events: none;
            position: absolute;
        }
        .auth-backdrop {
            background-color: #1e40af;
        }
    </style>
</head>
<body class="min-h-screen auth-backdrop" x-data="{ mode: '{{ $mode ?? 'login' }}', referralCode: '{{ $referralCode ?? '' }}' }">
    <div class="min-h-screen flex">
        <!-- Left Panel - Forms -->
        <div class="w-full lg:w-1/2 flex flex-col bg-white/95 backdrop-blur-sm">
            <!-- Header -->
            <div class="p-6 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-kab.png') }}" alt="Logo" class="h-10 w-auto">
                    <div>
                        <span class="font-bold text-gray-900 block">{{ config('app.name') }}</span>
                        <span class="text-xs text-gray-500">Pemerintah Kabupaten Kubu Raya</span>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="flex-1 flex items-center justify-center p-8">
                <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

                <!-- Login Form -->
                <div class="auth-panel" :class="mode !== 'login' && 'panel-hidden'" x-show="mode === 'login'">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
                    <p class="text-sm text-gray-500 mb-6">Silakan masukkan kredensial Anda</p>

                    @if(session('success'))
                    <div class="mb-4 p-3 bg-success-50 border border-success-200 text-success-700 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-4 p-3 bg-danger-50 border border-danger-200 text-danger-700 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="input w-full @error('email') border-danger-500 @enderror">
                            @error('email')
                            <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required
                                   class="input w-full @error('password') border-danger-500 @enderror">
                            @error('password')
                            <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600">
                                <span class="text-sm text-gray-600">Ingat saya</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:underline">Lupa password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-full">Masuk</button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        Belum punya akun? 
                        <button type="button" @click="mode = 'referral'" class="text-primary-600 hover:underline font-medium">Daftar</button>
                    </p>
                </div>

                <!-- Referral Code Form -->
                <div class="auth-panel" :class="mode !== 'referral' && 'panel-hidden'" x-show="mode === 'referral'"
                     x-data="{ 
                        checking: false, 
                        error: '', 
                        referrerName: '',
                        async validateReferral() {
                            if (!referralCode || referralCode.length < 8) {
                                this.error = 'Kode referral minimal 8 karakter';
                                return;
                            }
                            this.checking = true;
                            this.error = '';
                            try {
                                const res = await fetch('/api/validate-referral?code=' + encodeURIComponent(referralCode));
                                const data = await res.json();
                                if (data.valid) {
                                    this.referrerName = data.referrer_name;
                                    mode = 'register';
                                } else {
                                    this.error = data.message || 'Kode referral tidak valid';
                                }
                            } catch (e) {
                                this.error = 'Gagal memvalidasi kode referral';
                            }
                            this.checking = false;
                        }
                     }">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Kode Referral</h2>
                    <p class="text-sm text-gray-500 mb-6">Masukkan kode referral untuk mendaftar</p>

                    <form @submit.prevent="validateReferral()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Referral</label>
                            <input type="text" x-model="referralCode" required
                                   class="input w-full uppercase tracking-wider text-center text-base font-mono"
                                   :class="error && 'border-danger-500'"
                                   maxlength="20" minlength="8"
                                   :disabled="checking">
                            <p class="text-xs text-gray-500 mt-2" x-show="!error">Minta kode referral dari admin atau pengguna yang sudah terdaftar</p>
                            <p class="text-xs text-danger-600 mt-2" x-show="error" x-text="error"></p>
                        </div>

                        <button type="submit" class="btn btn-primary w-full" :disabled="!referralCode || checking">
                            <span x-show="!checking">Validasi Kode</span>
                            <span x-show="checking" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memvalidasi...
                            </span>
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        Sudah punya akun? 
                        <button type="button" @click="mode = 'login'" class="text-primary-600 hover:underline font-medium">Masuk</button>
                    </p>
                </div>

                <!-- Register Form -->
                <div class="auth-panel" :class="mode !== 'register' && 'panel-hidden'" x-show="mode === 'register'">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                    <p class="text-sm text-gray-500 mb-6">Kode Referral: <span class="font-mono font-medium" x-text="referralCode"></span></p>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="referral_code" :value="referralCode">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="input w-full @error('name') border-danger-500 @enderror">
                            @error('name')
                            <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="input w-full @error('email') border-danger-500 @enderror">
                            @error('email')
                            <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" required
                                       class="input w-full @error('password') border-danger-500 @enderror">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi</label>
                                <input type="password" name="password_confirmation" required class="input w-full">
                            </div>
                        </div>
                        @error('password')
                        <p class="text-xs text-danger-600 -mt-2">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="btn btn-primary w-full">Daftar</button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        <button type="button" @click="mode = 'referral'" class="text-primary-600 hover:underline font-medium">Ubah Kode Referral</button>
                        <span class="mx-2">|</span>
                        <button type="button" @click="mode = 'login'" class="text-primary-600 hover:underline font-medium">Masuk</button>
                    </p>
                </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 text-center text-xs text-gray-400 border-t border-gray-100">
                &copy; {{ date('Y') }} Pemerintah Kabupaten Kubu Raya • {{ config('app.name') }}
            </div>
        </div>

        <!-- Right Panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12">
            <div class="text-center text-white">
                <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-28 w-auto mx-auto mb-6">
                
                <h1 class="text-2xl font-bold mb-2">Sistem Inventaris Barang</h1>
                <p class="text-white/80 text-sm">
                    Pemerintah Kabupaten Kubu Raya
                </p>
            </div>
        </div>
    </div>
</body>
</html>
