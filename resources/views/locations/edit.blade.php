<x-app-layout title="Edit Lokasi">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('locations.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Edit Lokasi: {{ $location->name }}</h2>
        </div>

        <div class="card">
            <form action="{{ route('locations.update', $location) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Kode Lokasi" name="code" :value="$location->code" required />
                        <x-form.input label="Nama Lokasi" name="name" :value="$location->name" required />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-form.input label="Gedung" name="building" :value="$location->building" />
                        <x-form.input label="Lantai" name="floor" :value="$location->floor" />
                        <x-form.input label="Ruang" name="room" :value="$location->room" />
                    </div>

                    <x-form.textarea label="Deskripsi" name="description" :value="$location->description" rows="2" />

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $location->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <label class="text-sm text-gray-700">Lokasi aktif</label>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('locations.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
