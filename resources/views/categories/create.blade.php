<x-app-layout title="Tambah Kategori">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Tambah Kategori Baru</h2>
        </div>

        <div class="card">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input 
                            label="Kode Kategori" 
                            name="code" 
                            placeholder="Contoh: KOM"
                            required
                            helper="Maksimal 10 karakter, harus unik"
                        />

                        <x-form.input 
                            label="Nama Kategori" 
                            name="name" 
                            placeholder="Nama kategori"
                            required
                        />
                    </div>

                    <x-form.select 
                        label="Parent Kategori" 
                        name="parent_id"
                        placeholder="Pilih parent (opsional)"
                        helper="Kosongkan jika ini kategori utama"
                    >
                        @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.textarea 
                        label="Deskripsi" 
                        name="description" 
                        placeholder="Deskripsi kategori (opsional)"
                        rows="3"
                    />

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <label class="text-sm text-gray-700">Kategori aktif</label>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
