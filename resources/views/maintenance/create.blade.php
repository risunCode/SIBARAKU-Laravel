<x-app-layout title="Catat Maintenance">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('maintenance.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Catat Maintenance Barang</h2>
        </div>

        <div class="card">
            <form action="{{ route('maintenance.store') }}" method="POST">
                @csrf
                <div class="card-body space-y-4">
                    <x-form.select label="Pilih Barang" name="commodity_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($commodities as $commodity)
                        <option value="{{ $commodity->id }}" {{ ($selectedCommodity?->id ?? old('commodity_id')) == $commodity->id ? 'selected' : '' }}>
                            {{ $commodity->item_code }} - {{ $commodity->name }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.input label="Jenis Maintenance" name="maintenance_type" required placeholder="Contoh: Servis rutin, Perbaikan, Penggantian part" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Tanggal Maintenance" name="maintenance_date" type="date" required :value="date('Y-m-d')" />
                        <x-form.input label="Biaya (Rp)" name="cost" type="number" min="0" placeholder="0" />
                    </div>

                    <x-form.select label="Kondisi Setelah Maintenance" name="condition_after" required :options="[
                        'baik' => 'Baik',
                        'rusak_ringan' => 'Rusak Ringan',
                        'rusak_berat' => 'Rusak Berat'
                    ]" />

                    <x-form.input label="Dilakukan Oleh" name="performed_by" placeholder="Nama teknisi/vendor" />

                    <x-form.textarea label="Deskripsi Pekerjaan" name="description" rows="3" placeholder="Jelaskan pekerjaan maintenance yang dilakukan..." />

                    <x-form.input label="Jadwal Maintenance Berikutnya" name="next_maintenance_date" type="date" helper="Opsional - untuk pengingat" />
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('maintenance.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
