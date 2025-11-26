<x-app-layout title="Ajukan Penghapusan">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('disposals.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Ajukan Penghapusan Barang</h2>
        </div>

        <div class="card">
            <form action="{{ route('disposals.store') }}" method="POST">
                @csrf
                <div class="card-body space-y-4">
                    <x-form.select label="Pilih Barang" name="commodity_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($commodities as $commodity)
                        <option value="{{ $commodity->id }}" {{ ($selectedCommodity?->id ?? old('commodity_id')) == $commodity->id ? 'selected' : '' }}>
                            {{ $commodity->item_code }} - {{ $commodity->name }} ({{ $commodity->condition_label }})
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Alasan Penghapusan" name="reason" required :options="[
                        'rusak_berat' => 'Rusak Berat / Tidak Dapat Diperbaiki',
                        'hilang' => 'Hilang',
                        'usang' => 'Usang / Tidak Layak Pakai',
                        'dicuri' => 'Dicuri',
                        'dijual' => 'Dijual',
                        'dihibahkan' => 'Dihibahkan',
                        'lainnya' => 'Lainnya'
                    ]" />

                    <x-form.textarea label="Justifikasi" name="description" required rows="4" placeholder="Jelaskan alasan detail mengapa barang ini harus dihapus dari inventaris..." />

                    <x-form.input label="Taksiran Nilai Sisa (Rp)" name="estimated_value" type="number" min="0" placeholder="0" helper="Perkiraan nilai barang saat ini" />

                    <x-form.textarea label="Catatan Tambahan" name="notes" rows="2" placeholder="Catatan tambahan (opsional)" />
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('disposals.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-danger">Ajukan Penghapusan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
