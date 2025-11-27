<x-app-layout title="Ajukan Penghapusan">
    <div class="max-w-4xl mx-auto">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Taksiran Nilai Sisa (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">Rp</span>
                            <input type="text" id="estimated_value_display" 
                                   class="input pl-10" 
                                   placeholder="0"
                                   oninput="formatRupiahInput(this, 'estimated_value')">
                            <input type="hidden" name="estimated_value" id="estimated_value" value="{{ old('estimated_value', 0) }}">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Perkiraan nilai barang saat ini</p>
                    </div>

                    <x-form.textarea label="Catatan Tambahan" name="notes" rows="2" placeholder="Catatan tambahan (opsional)" />
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('disposals.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-danger">Ajukan Penghapusan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function formatRupiahInput(input, hiddenId) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
            document.getElementById(hiddenId).value = value.replace(/\./g, '') || 0;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const hiddenVal = document.getElementById('estimated_value');
            const displayVal = document.getElementById('estimated_value_display');
            if (hiddenVal.value && hiddenVal.value > 0) {
                displayVal.value = parseInt(hiddenVal.value).toLocaleString('id-ID');
            }
        });
    </script>
    @endpush
</x-app-layout>
