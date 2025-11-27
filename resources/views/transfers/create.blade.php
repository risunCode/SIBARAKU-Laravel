<x-app-layout title="Ajukan Transfer">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('transfers.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Ajukan Transfer Barang</h2>
        </div>

        <div class="card">
            <form action="{{ route('transfers.store') }}" method="POST">
                @csrf
                <div class="card-body space-y-4">
                    <x-form.select label="Pilih Barang" name="commodity_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($commodities as $commodity)
                        <option value="{{ $commodity->id }}" {{ ($selectedCommodity?->id ?? old('commodity_id')) == $commodity->id ? 'selected' : '' }}>
                            {{ $commodity->item_code }} - {{ $commodity->name }} ({{ $commodity->location->name }})
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Lokasi Tujuan" name="to_location_id" required>
                        <option value="">-- Pilih Lokasi Tujuan --</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ old('to_location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.textarea label="Alasan Transfer" name="reason" required rows="3" placeholder="Jelaskan alasan perpindahan barang ini..." />

                    <x-form.textarea label="Catatan Tambahan" name="notes" rows="2" placeholder="Catatan tambahan (opsional)" />
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('transfers.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Ajukan Transfer</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
