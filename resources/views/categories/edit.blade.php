<x-app-layout title="Edit Kategori">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Edit Kategori: {{ $category->name }}</h2>
        </div>

        <div class="card">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input 
                            label="Kode Kategori" 
                            name="code" 
                            :value="$category->code"
                            required
                        />

                        <x-form.input 
                            label="Nama Kategori" 
                            name="name" 
                            :value="$category->name"
                            required
                        />
                    </div>

                    <x-form.select 
                        label="Parent Kategori" 
                        name="parent_id"
                        :value="$category->parent_id"
                        placeholder="Pilih parent (opsional)"
                    >
                        @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.textarea 
                        label="Deskripsi" 
                        name="description" 
                        :value="$category->description"
                        rows="3"
                    />

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <label class="text-sm text-gray-700">Kategori aktif</label>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
