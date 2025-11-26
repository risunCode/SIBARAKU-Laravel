<x-guest-layout title="Reset Password">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
        <p class="text-sm text-gray-500 mt-1">Masukkan password baru Anda</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-form.input 
            label="Password Baru" 
            name="password" 
            type="password" 
            placeholder="Minimal 8 karakter"
            required 
        />

        <x-form.input 
            label="Konfirmasi Password" 
            name="password_confirmation" 
            type="password" 
            placeholder="Ulangi password baru"
            required 
        />

        <button type="submit" class="btn btn-primary w-full">
            Reset Password
        </button>
    </form>
</x-guest-layout>
