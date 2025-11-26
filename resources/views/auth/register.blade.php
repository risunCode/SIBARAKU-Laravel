<x-guest-layout title="Daftar">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Daftar Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-1">Direferensikan oleh: <strong>{{ $referrer->name }}</strong></p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="referral_code" value="{{ $referralCode }}">

        <x-form.input 
            label="Nama Lengkap" 
            name="name" 
            :value="old('name')"
            placeholder="Masukkan nama lengkap"
            required 
            autofocus 
        />

        <x-form.input 
            label="Email" 
            name="email" 
            type="email" 
            :value="old('email')"
            placeholder="nama@email.com"
            required 
        />

        <x-form.input 
            label="No. Telepon" 
            name="phone" 
            :value="old('phone')"
            placeholder="08xxxxxxxxxx"
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.input 
                label="Password" 
                name="password" 
                type="password" 
                placeholder="Minimal 8 karakter"
                required 
            />

            <x-form.input 
                label="Konfirmasi Password" 
                name="password_confirmation" 
                type="password" 
                placeholder="Ulangi password"
                required 
            />
        </div>

        <div class="border-t border-gray-200 pt-4 mt-4">
            <p class="text-sm font-medium text-gray-700 mb-3">Pertanyaan Keamanan</p>
            <p class="text-xs text-gray-500 mb-3">Digunakan untuk reset password jika lupa</p>

            <x-form.select 
                label="Pertanyaan Keamanan 1" 
                name="security_question_1"
                :value="old('security_question_1')"
                :options="$securityQuestions"
                required
            />
            <x-form.input 
                label="Jawaban 1" 
                name="security_answer_1" 
                :value="old('security_answer_1')"
                placeholder="Masukkan jawaban"
                required 
                class="mt-2"
            />

            <x-form.select 
                label="Pertanyaan Keamanan 2" 
                name="security_question_2"
                :value="old('security_question_2')"
                :options="$securityQuestions"
                required
                class="mt-4"
            />
            <x-form.input 
                label="Jawaban 2" 
                name="security_answer_2" 
                :value="old('security_answer_2')"
                placeholder="Masukkan jawaban"
                required 
                class="mt-2"
            />
        </div>

        <button type="submit" class="btn btn-primary w-full">
            Daftar
        </button>

        <p class="text-center text-sm text-gray-500">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-primary-600 hover:underline">Masuk</a>
        </p>
    </form>
</x-guest-layout>
