<x-guest-layout title="Login">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Masuk ke Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masukkan email dan password Anda</p>
    </div>

    @if (session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    @if (session('error'))
        <x-alert type="error" :message="session('error')" class="mb-4" />
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-form.input 
            label="Email" 
            name="email" 
            type="email" 
            :value="old('email')"
            placeholder="nama@email.com"
            required 
            autofocus 
        />

        <x-form.input 
            label="Password" 
            name="password" 
            type="password" 
            placeholder="Masukkan password"
            required 
        />

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-gray-600">Ingat saya</span>
            </label>

            <a href="{{ route('password.reset.auth') }}" class="text-sm text-primary-600 hover:underline">
                Lupa password?
            </a>
        </div>

        <button type="submit" class="btn btn-primary w-full">
            Masuk
        </button>
    </form>
</x-guest-layout>
