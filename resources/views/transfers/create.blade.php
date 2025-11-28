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

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                            Lokasi Tujuan <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <select name="to_location_id" id="locationSelect" class="input w-full" onchange="toggleCustomLocation()">
                                <option value="">-- Pilih Lokasi Tujuan --</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('to_location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }} - {{ $location->building ?? 'Gedung' }} {{ $location->floor ?? '' }} {{ $location->room ?? '' }}
                                </option>
                                @endforeach
                                <option value="custom" {{ old('to_location_id') == 'custom' ? 'selected' : '' }}>🏷️ Input Manual / Lainnya</option>
                            </select>
                            
                            <div id="customLocationInput" class="hidden">
                                <input type="text" name="custom_location" id="customLocation" 
                                       placeholder="Contoh: Ruang Server Lt.3, Gudang Belakang, dll..." 
                                       class="input w-full" 
                                       value="{{ old('custom_location') }}">
                                <p class="text-xs mt-1" style="color: var(--text-secondary);">Masukkan lokasi tujuan transfer</p>
                            </div>
                        </div>
                        @error('to_location_id')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        @error('custom_location')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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

    <script>
        // Toggle custom location input
        function toggleCustomLocation() {
            const select = document.getElementById('locationSelect');
            const customInput = document.getElementById('customLocationInput');
            const customField = document.getElementById('customLocation');
            
            if (select.value === 'custom') {
                customInput.classList.remove('hidden');
                customField.setAttribute('required', 'required');
            } else {
                customInput.classList.add('hidden');
                customField.removeAttribute('required');
                customField.value = '';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomLocation();
        });
    </script>
</x-app-layout>
