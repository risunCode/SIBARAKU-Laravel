<x-app-layout title="Tambah Lokasi">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('locations.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Tambah Lokasi Baru</h2>
        </div>

        <div class="card">
            <form action="{{ route('locations.store') }}" method="POST">
                @csrf
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Kode Lokasi" name="code" placeholder="Contoh: R-KRJ1" required />
                        <x-form.input label="Nama Lokasi" name="name" placeholder="Nama lokasi" required />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-form.input label="Gedung" name="building" placeholder="Nama gedung" />
                        <x-form.input label="Lantai" name="floor" placeholder="Lantai 1" />
                        <x-form.input label="Ruang" name="room" placeholder="R.101" />
                    </div>

                    <x-form.textarea label="Deskripsi" name="description" rows="2" />

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-primary-600">
                        <label class="text-sm text-gray-700">Lokasi aktif</label>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('locations.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
