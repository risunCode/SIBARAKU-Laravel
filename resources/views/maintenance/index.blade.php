<x-app-layout title="Maintenance">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Log Maintenance</h2>
            <p class="text-sm text-gray-500">Catatan pemeliharaan dan perbaikan barang</p>
        </div>

        @can('maintenance.create')
        <button onclick="openModal('modal-create-maintenance')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Catat Maintenance
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form id="filterForm" action="{{ route('maintenance.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4" data-no-warn>
                <div class="relative">
                    <input type="text" name="search" id="searchInput" class="input w-full pl-10" placeholder="Cari barang..." value="{{ request('search') }}" oninput="debounceSearch()">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                <select name="status" class="input w-full" onchange="submitFilter()">
                    <option value="">Semua Status</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Mendatang</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                </select>

                <a href="{{ route('maintenance.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>Barang</th>
                        <th>Jenis Maintenance</th>
                        <th>Tanggal</th>
                        <th>Biaya</th>
                        <th>Kondisi Setelah</th>
                        <th>Jadwal Berikutnya</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $index => $log)
                    <tr>
                        <td class="text-gray-500">{{ $maintenances->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('commodities.show', $log->commodity) }}" class="text-primary-600 hover:underline">
                                {{ Str::limit($log->commodity->name, 25) }}
                            </a>
                        </td>
                        <td>{{ $log->maintenance_type }}</td>
                        <td>{{ $log->maintenance_date->format('d M Y') }}</td>
                        <td>{{ $log->formatted_cost }}</td>
                        <td><span class="badge {{ $log->condition_after === 'baik' ? 'badge-success' : 'badge-warning' }}">{{ $log->condition_after_label }}</span></td>
                        <td>
                            @if($log->next_maintenance_date)
                                <span class="{{ $log->isOverdue() ? 'text-danger-600 font-medium' : '' }}">
                                    {{ $log->next_maintenance_date->format('d M Y') }}
                                    @if($log->isOverdue())
                                    <span class="badge badge-danger">Terlambat</span>
                                    @endif
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('maintenance.show', $log) }}" class="btn btn-sm btn-outline">Detail</a>
                                @can('maintenance.edit')
                                <a href="{{ route('maintenance.edit', $log) }}" class="btn btn-sm btn-outline">Edit</a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <x-empty-state 
                                icon="maintenance"
                                title="Belum Ada Data Maintenance"
                                description="Mulai catat pemeliharaan dan perbaikan barang untuk tracking yang lebih baik"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($maintenances->hasPages() || $maintenances->count() > 0)
        <div class="card-footer">
            <x-pagination :paginator="$maintenances" />
        </div>
        @endif
    </div>

    <!-- Create Maintenance Modal -->
    <x-modal name="modal-create-maintenance" title="Catat Maintenance Barang" maxWidth="5xl">
        <form action="{{ route('maintenance.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <x-form.select label="Pilih Barang" name="commodity_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($commodities ?? [] as $commodity)
                        <option value="{{ $commodity->id }}" {{ old('commodity_id') == $commodity->id ? 'selected' : '' }}>
                            {{ $commodity->item_code }} - {{ $commodity->name }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.input label="Jenis Maintenance" name="maintenance_type" required placeholder="Contoh: Servis rutin, Perbaikan, Penggantian part" />

                    <x-form.input label="Tanggal Maintenance" name="maintenance_date" type="date" required :value="date('Y-m-d')" />
                </div>

                <!-- Middle Column -->
                <div class="space-y-4">
                    <x-form.input label="Biaya (Rp)" name="cost" type="number" min="0" placeholder="0" />

                    <x-form.input label="Penanggung Jawab" name="technician" placeholder="Nama teknisi/penanggung jawab" />

                    <x-form.input label="Tanggal Maintenance Berikutnya" name="next_maintenance" type="date" />
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <x-form.textarea label="Keterangan Maintenance" name="description" rows="4" placeholder="Jelaskan detail pekerjaan maintenance yang dilakukan..." />

                    <x-form.textarea label="Catatan Tambahan" name="notes" rows="4" placeholder="Catatan tambahan (opsional)" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-maintenance')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Maintenance</button>
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
