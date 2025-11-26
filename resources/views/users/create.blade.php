<x-app-layout title="Tambah Pengguna">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Tambah Pengguna Baru</h2>
        </div>

        <div class="card">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Nama Lengkap" name="name" required />
                        <x-form.input label="Email" name="email" type="email" required />
                    </div>

                    <x-form.input label="No. Telepon" name="phone" placeholder="08xxxxxxxxxx" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Password" name="password" type="password" required />
                        <x-form.input label="Konfirmasi Password" name="password_confirmation" type="password" required />
                    </div>

                    <x-form.select label="Role" name="role" required :options="$roles->pluck('name', 'name')->map(fn($v) => ucfirst($v))->toArray()" />

                    <div>
                        <label class="form-label">Avatar</label>
                        <input type="file" name="avatar" accept="image/*" class="text-sm">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-primary-600">
                        <label class="text-sm text-gray-700">Pengguna aktif</label>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Pertanyaan Keamanan</p>

                        <x-form.select label="Pertanyaan 1" name="security_question_1" :options="$securityQuestions" required />
                        <x-form.input label="Jawaban 1" name="security_answer_1" required class="mt-2" />

                        <x-form.select label="Pertanyaan 2" name="security_question_2" :options="$securityQuestions" required class="mt-4" />
                        <x-form.input label="Jawaban 2" name="security_answer_2" required class="mt-2" />
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
