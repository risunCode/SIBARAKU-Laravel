<x-guest-layout title="Pertanyaan Keamanan">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Pertanyaan Keamanan</h2>
        <p class="text-sm text-gray-500 mt-1">Jawab pertanyaan keamanan untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('password.security', $token) }}" class="space-y-4">
        @csrf

        <div>
            <label class="form-label">{{ $question1 }}</label>
            <input type="text" name="answer1" class="form-input" placeholder="Jawaban pertama" required autofocus>
            @error('answer1')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="form-label">{{ $question2 }}</label>
            <input type="text" name="answer2" class="form-input" placeholder="Jawaban kedua" required>
            @error('answer2')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <p class="text-xs text-gray-500">
            Jawaban bersifat case-insensitive (huruf besar/kecil tidak berpengaruh)
        </p>

        <button type="submit" class="btn btn-primary w-full">
            Verifikasi
        </button>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-primary-600 hover:underline">Kembali ke login</a>
        </p>
    </form>
</x-guest-layout>
