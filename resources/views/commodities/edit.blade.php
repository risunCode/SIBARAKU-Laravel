<x-app-layout title="Edit Barang">
    <div class="max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('commodities.show', $commodity) }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Edit: {{ $commodity->name }}</h2>
            <p class="text-sm text-gray-500">Kode: {{ $commodity->item_code }}</p>
        </div>

        <form action="{{ route('commodities.update', $commodity) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="card-body space-y-4">
                    <x-form.input label="Nama Barang" name="name" :value="$commodity->name" required />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.select label="Kategori" name="category_id" :value="$commodity->category_id" required>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $commodity->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </x-form.select>

                        <x-form.select label="Lokasi" name="location_id" :value="$commodity->location_id" required>
                            @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id', $commodity->location_id) == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                            @endforeach
                        </x-form.select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-form.input label="Merk/Brand" name="brand" :value="$commodity->brand" />
                        <x-form.input label="Model/Tipe" name="model" :value="$commodity->model" />
                        <x-form.input label="Serial Number" name="serial_number" :value="$commodity->serial_number" />
                    </div>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Detail Perolehan</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.select label="Cara Perolehan" name="acquisition_type" :value="$commodity->acquisition_type" required :options="[
                            'pembelian' => 'Pembelian',
                            'hibah' => 'Hibah',
                            'bantuan' => 'Bantuan',
                            'produksi' => 'Produksi Sendiri',
                            'lainnya' => 'Lainnya'
                        ]" />
                        <x-form.input label="Sumber Perolehan" name="acquisition_source" :value="$commodity->acquisition_source" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-form.input label="Tahun Perolehan" name="purchase_year" type="number" :value="$commodity->purchase_year" />
                        <x-form.input label="Harga Perolehan (Rp)" name="purchase_price" type="number" :value="$commodity->purchase_price" />
                        <x-form.input label="Jumlah" name="quantity" type="number" min="1" :value="$commodity->quantity" required />
                    </div>

                    <x-form.select label="Kondisi" name="condition" :value="$commodity->condition" required :options="[
                        'baik' => 'Baik',
                        'rusak_ringan' => 'Rusak Ringan',
                        'rusak_berat' => 'Rusak Berat'
                    ]" />
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Informasi Tambahan</h3>
                </div>
                <div class="card-body space-y-4">
                    <x-form.input label="Penanggung Jawab" name="responsible_person" :value="$commodity->responsible_person" />
                    <x-form.textarea label="Spesifikasi" name="specifications" :value="$commodity->specifications" rows="3" />
                    <x-form.textarea label="Catatan" name="notes" :value="$commodity->notes" rows="2" />
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Foto Barang</h3>
                </div>
                <div class="card-body space-y-4">
                    @if($commodity->images->count() > 0)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($commodity->images as $image)
                        <div class="relative group">
                            <img src="{{ $image->url }}" alt="" class="w-full h-24 object-cover rounded-lg {{ $image->is_primary ? 'ring-2 ring-primary-500' : '' }}">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="primary_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }} class="sr-only">
                                    <span class="text-white text-xs bg-primary-600 px-2 py-1 rounded">Utama</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only">
                                    <span class="text-white text-xs bg-danger-600 px-2 py-1 rounded">Hapus</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div>
                        <label class="form-label">Tambah Foto Baru</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="text-sm">
                        <p class="text-xs text-gray-500 mt-1">Upload maksimal 5 gambar (JPG, PNG, max 2MB per file)</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('commodities.show', $commodity) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-app-layout>
