<x-guest-layout title="Lupa Password">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password</h2>
        <p class="text-sm text-gray-500 mt-1">Masukkan email untuk reset password</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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

        <button type="submit" class="btn btn-primary w-full">
            Lanjutkan
        </button>

        <p class="text-center text-sm text-gray-500">
            Ingat password? 
            <a href="{{ route('login') }}" class="text-primary-600 hover:underline">Kembali ke login</a>
        </p>
    </form>
</x-guest-layout>
