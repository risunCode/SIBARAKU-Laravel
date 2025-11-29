@section('meta-description', 'Sistem transfer barang inventaris antar lokasi. Proses pengajuan, persetujuan, dan tracking perpindahan aset dengan workflow yang terstruktur.')
<x-app-layout title="Transfer Barang">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Transfer Barang</h2>
            <p class="text-sm text-gray-500">Kelola perpindahan barang antar lokasi</p>
        </div>

        @can('transfers.create')
        <button onclick="openModal('modal-create-transfer')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Transfer
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form id="filterForm" action="{{ route('transfers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4" data-no-warn>
                <div class="relative">
                    <input type="text" name="search" id="searchInput" class="input w-full pl-10" placeholder="Cari nomor transfer..." value="{{ request('search') }}" oninput="debounceSearch()">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                <select name="status" class="input w-full" onchange="submitFilter()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <a href="{{ route('transfers.index') }}" class="btn btn-outline">Reset</a>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-12">No</th>
                        <th>No. Transfer</th>
                        <th>Barang</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $index => $transfer)
                    <tr>
                        <td class="text-gray-600">{{ $transfers->firstItem() + $index }}</td>
                        <td class="font-mono text-xs">{{ $transfer->transfer_number }}</td>
                        <td>
                            @if($transfer->commodity)
                            <div class="flex items-center gap-3">
                                @php
                                    $primaryImage = $transfer->commodity->images()->where('is_primary', true)->first();
                                @endphp
                                @if($primaryImage)
                                <img src="{{ Storage::url($primaryImage->image_path) }}" 
                                     alt="{{ $transfer->commodity->name }}" 
                                     class="w-10 h-10 rounded-lg object-cover">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('commodities.show', $transfer->commodity) }}" class="text-primary-600 hover:underline font-medium">
                                        {{ Str::limit($transfer->commodity->name, 25) }}
                                    </a>
                                    <p class="text-xs text-gray-500 font-mono">{{ $transfer->commodity->item_code }}</p>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400">Barang tidak tersedia</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $transfer->status_badge_class }}">{{ $transfer->status_label }}</span></td>
                        <td class="text-gray-600">{{ $transfer->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('transfers.show', $transfer) }}" class="btn btn-sm btn-outline">Detail</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <x-empty-state 
                                icon="transfer"
                                title="Belum Ada Transfer"
                                description="Buat transfer pertama Anda untuk memindahkan barang antar lokasi"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transfers->hasPages() || $transfers->count() > 0)
        <div class="card-footer">
            <x-pagination :paginator="$transfers" />
        </div>
        @endif
    </div>

    <!-- Create Transfer Modal -->
    <x-modal name="modal-create-transfer" title="Ajukan Transfer Barang" maxWidth="4xl">
        <form action="{{ route('transfers.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <x-form.select label="Pilih Barang" name="commodity_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($commodities ?? [] as $commodity)
                        <option value="{{ $commodity->id }}" {{ old('commodity_id') == $commodity->id ? 'selected' : '' }}>
                            {{ $commodity->item_code }} - {{ $commodity->name }} ({{ $commodity->location->name }})
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Lokasi Tujuan" name="to_location_id" required>
                        <option value="">-- Pilih Lokasi Tujuan --</option>
                        @foreach($locations ?? [] as $location)
                        <option value="{{ $location->id }}" {{ old('to_location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                        @endforeach
                    </x-form.select>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <x-form.textarea label="Alasan Transfer" name="reason" required rows="3" placeholder="Jelaskan alasan perpindahan barang ini..." />

                    <x-form.textarea label="Catatan Tambahan" name="notes" rows="3" placeholder="Catatan tambahan (opsional)" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-transfer')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Ajukan Transfer</button>
            </div>
        </form>
    </x-modal>

    <script>
        function submitFilter() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams();
            
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            
            window.location.href = form.action + '?' + params.toString();
        }

        let searchTimeout;
        function debounceSearch() {
            const spinner = document.getElementById('searchSpinner');
            if (spinner) spinner.classList.remove('hidden');
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                submitFilter();
            }, 500);
        }
    </script>
</x-app-layout>
