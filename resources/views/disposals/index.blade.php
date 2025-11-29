<x-app-layout title="Penghapusan Barang">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Penghapusan Barang</h2>
            <p class="text-sm text-gray-500">Kelola pengajuan penghapusan barang inventaris</p>
        </div>

        @can('disposals.create')
        <button onclick="openModal('modal-create-disposal')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Penghapusan
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form id="filterForm" action="{{ route('disposals.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4" data-no-warn>
                <div class="relative">
                    <input type="text" name="search" id="searchInput" class="input w-full pl-10" placeholder="Cari nomor/barang..." value="{{ request('search') }}" oninput="debounceSearch()">
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
                </select>

                <a href="{{ route('disposals.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>No. Pengajuan</th>
                        <th>Barang</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposals as $index => $disposal)
                    <tr>
                        <td class="text-gray-500">{{ $disposals->firstItem() + $index }}</td>
                        <td class="font-mono text-xs">{{ $disposal->disposal_number }}</td>
                        <td>
                            @if($disposal->commodity)
                            <a href="{{ route('commodities.show', $disposal->commodity) }}" class="text-primary-600 hover:underline">
                                {{ Str::limit($disposal->commodity->name, 25) }}
                            </a>
                            @else
                            <span class="text-gray-400">Barang tidak tersedia</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $disposal->status_badge_class }}">{{ $disposal->status_label }}</span></td>
                        <td class="text-gray-500">{{ $disposal->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('disposals.show', $disposal) }}" class="btn btn-sm btn-outline">Detail</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <x-empty-state 
                                icon="document"
                                title="Belum Ada Pengajuan Penghapusan"
                                description="Kelola penghapusan barang yang sudah tidak layak pakai atau rusak berat"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($disposals->hasPages() || $disposals->count() > 0)
        <div class="card-footer">
            <x-pagination :paginator="$disposals" />
        </div>
        @endif
    </div>

    <!-- Create Disposal Modal -->
    <x-modal name="modal-create-disposal" title="Ajukan Penghapusan Barang" maxWidth="4xl">
        <form action="{{ route('disposals.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <x-form.select label="Pilih Barang" name="commodity_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($commodities ?? [] as $commodity)
                        <option value="{{ $commodity->id }}" {{ old('commodity_id') == $commodity->id ? 'selected' : '' }}>
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

                    <x-form.input label="Taksiran Nilai Sisa (Rp)" name="estimated_value" type="number" min="0" placeholder="0" helper="Perkiraan nilai barang saat ini" />
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <x-form.textarea label="Justifikasi" name="description" required rows="6" placeholder="Jelaskan alasan detail mengapa barang ini harus dihapus dari inventaris..." />

                    <x-form.textarea label="Catatan Tambahan" name="notes" rows="4" placeholder="Catatan tambahan (opsional)" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-disposal')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-danger">Ajukan Penghapusan</button>
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
