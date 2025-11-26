<x-app-layout title="Tambah Barang">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('commodities.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Tambah Barang Baru</h2>
        </div>

        <form action="{{ route('commodities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="return handleFormSubmit(this, event)">
            @csrf
            
            <!-- Grid Layout: 2 Kolom Utama -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                
                <!-- Left Column (2/3 width) -->
                <div class="xl:col-span-2 space-y-6">
                    
                    <!-- Informasi Dasar -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Informasi Dasar</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="lg:col-span-2">
                                <x-form.input label="Nama Barang" name="name" required placeholder="Masukkan nama barang" />
                            </div>
                            <x-form.select label="Kategori" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </x-form.select>
                            <x-form.select label="Lokasi" name="location_id" required>
                                <option value="">Pilih Lokasi</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                                @endforeach
                            </x-form.select>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
                            <x-form.input label="Merk/Brand" name="brand" placeholder="Contoh: HP, Dell" />
                            <x-form.input label="Model/Tipe" name="model" placeholder="Contoh: Pavilion 14" />
                            <x-form.input label="Serial Number" name="serial_number" placeholder="Nomor seri (opsional)" />
                        </div>
                    </div>

                    <!-- Detail Perolehan -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Detail Perolehan</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <x-form.select label="Cara Perolehan" name="acquisition_type" required :options="[
                                'pembelian' => 'Pembelian',
                                'hibah' => 'Hibah',
                                'bantuan' => 'Bantuan',
                                'produksi' => 'Produksi Sendiri',
                                'lainnya' => 'Lainnya'
                            ]" />
                            <x-form.input label="Sumber Perolehan" name="acquisition_source" placeholder="Contoh: PT ABC, Donatur" />
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
                            <x-form.input label="Tahun Perolehan" name="purchase_year" type="number" min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" />
                            <x-form.input label="Harga Perolehan (Rp)" name="purchase_price" type="number" min="0" placeholder="0" />
                            <x-form.input label="Jumlah" name="quantity" type="number" min="1" value="1" required />
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <x-form.select label="Kondisi" name="condition" required :options="[
                                'baik' => 'Baik',
                                'rusak_ringan' => 'Rusak Ringan',
                                'rusak_berat' => 'Rusak Berat'
                            ]" />
                            <x-form.input label="Penanggung Jawab" name="responsible_person" placeholder="Nama penanggung jawab barang" />
                        </div>
                    </div>

                </div>
                
                <!-- Right Column (1/3 width) -->
                <div class="space-y-6">
                    
                    <!-- Foto Barang -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Foto Barang</h3>
                        <div class="space-y-3">
                            <input type="file" name="images[]" id="images-upload" multiple accept="image/*,.pdf,.doc,.docx" class="w-full text-sm theme-input border rounded-lg p-2" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" onchange="previewMultipleFiles(this, 'preview-images')">
                            <p class="text-xs" style="color: var(--text-secondary);">Upload maksimal 5 file (JPG, PNG, PDF, DOC max 2MB per file). Gambar pertama akan menjadi foto utama.</p>
                            <div id="preview-images"></div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Informasi Tambahan</h3>
                        <div class="space-y-4">
                            <x-form.textarea label="Spesifikasi" name="specifications" rows="3" placeholder="Spesifikasi teknis barang (opsional)" />
                            <x-form.textarea label="Catatan" name="notes" rows="2" placeholder="Catatan tambahan (opsional)" />
                        </div>
                    </div>

                </div>
            </div>

            <!-- Submit Actions -->
            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('commodities.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
