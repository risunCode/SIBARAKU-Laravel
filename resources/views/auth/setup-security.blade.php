<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Setup Keamanan - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <!-- Progress -->
        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="w-8 h-8 rounded-full bg-success-500 text-white flex items-center justify-center text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="w-16 h-1 bg-success-500 rounded"></div>
            <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-medium">2</div>
            <div class="w-16 h-1 bg-gray-300 rounded"></div>
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-sm font-medium">3</div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Setup Keamanan Akun</h2>
                    <p class="text-sm text-gray-500 mt-1">Informasi ini digunakan untuk pemulihan akun</p>
                </div>

                <form method="POST" action="{{ route('auth.setup-security.store') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                               class="form-input @error('birth_date') border-danger-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Digunakan sebagai verifikasi identitas</p>
                        @error('birth_date')
                        <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pertanyaan Keamanan 1 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan Keamanan 1</label>
                        <select name="security_question_1" required
                                class="form-select @error('security_question_1') border-danger-500 @enderror">
                            <option value="">Pilih pertanyaan</option>
                            @foreach($securityQuestions as $key => $question)
                            <option value="{{ $key }}" {{ old('security_question_1') == $key ? 'selected' : '' }}>{{ $question }}</option>
                            @endforeach
                        </select>
                        @error('security_question_1')
                        <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban 1</label>
                        <input type="text" name="security_answer_1" value="{{ old('security_answer_1') }}" required
                               class="form-input @error('security_answer_1') border-danger-500 @enderror">
                        @error('security_answer_1')
                        <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pertanyaan Keamanan 2 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan Keamanan 2</label>
                        <select name="security_question_2" required
                                class="form-select @error('security_question_2') border-danger-500 @enderror">
                            <option value="">Pilih pertanyaan</option>
                            @foreach($securityQuestions as $key => $question)
                            <option value="{{ $key }}" {{ old('security_question_2') == $key ? 'selected' : '' }}>{{ $question }}</option>
                            @endforeach
                        </select>
                        @error('security_question_2')
                        <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban 2</label>
                        <input type="text" name="security_answer_2" value="{{ old('security_answer_2') }}" required
                               class="form-input @error('security_answer_2') border-danger-500 @enderror">
                        @error('security_answer_2')
                        <p class="text-xs text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Custom Question (Optional) -->
                    <div class="pt-4 border-t border-gray-200">
                        <label class="flex items-center gap-2 mb-3">
                            <input type="checkbox" name="use_custom_question" value="1" x-data x-model="useCustom" class="rounded border-gray-300 text-primary-600">
                            <span class="text-sm text-gray-700">Tambah pertanyaan kustom</span>
                        </label>
                        
                        <div x-data="{ useCustom: false }" x-show="useCustom" class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan Kustom</label>
                                <input type="text" name="custom_question" value="{{ old('custom_question') }}"
                                       class="form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban</label>
                                <input type="text" name="custom_answer" value="{{ old('custom_answer') }}"
                                       class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 mt-6 rounded-b-xl border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-4">Jawaban tidak peka huruf besar/kecil dan akan disimpan secara terenkripsi.</p>
                        <button type="submit" class="btn btn-primary w-full">Simpan dan Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-gray-500 mt-6">
            {{ config('app.name') }} &copy; {{ date('Y') }}
        </p>
    </div>
</body>
</html>
