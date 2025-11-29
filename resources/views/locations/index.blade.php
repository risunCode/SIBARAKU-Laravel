@section('meta-description', 'Kelola lokasi penyimpanan barang inventaris. Tracking gedung, lantai, ruangan, dan PIC untuk manajemen aset yang terstruktur.')
<x-app-layout title="Lokasi">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Lokasi</h2>
            <p class="text-sm text-gray-600">Kelola lokasi penyimpanan barang</p>
        </div>

        @can('locations.create')
        <button onclick="openCreateModal()" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Lokasi
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form id="filterForm" action="{{ route('locations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4" data-no-warn>
                <div class="relative">
                    <input type="text" name="search" id="searchInput" class="input w-full pl-10" placeholder="Cari nama/kode..." value="{{ request('search') }}" oninput="debounceSearch()">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                <select name="building" class="input w-full" onchange="submitFilter()">
                    <option value="">Semua Gedung</option>
                    @foreach($buildings as $building)
                    <option value="{{ $building }}" {{ request('building') == $building ? 'selected' : '' }}>{{ $building }}</option>
                    @endforeach
                </select>

                <a href="{{ route('locations.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>
                            <a href="{{ route('locations.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'code', 'direction' => (request('sort') === 'code' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-blue-600">
                                Kode
                                @if(request('sort') === 'code')
                                    <span class="text-xs">{{ request('direction') === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('locations.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'name', 'direction' => (request('sort') === 'name' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-blue-600">
                                Nama
                                @if(request('sort') === 'name' || !request('sort'))
                                    <span class="text-xs">{{ (request('direction') ?? 'asc') === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('locations.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'building', 'direction' => (request('sort') === 'building' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-blue-600">
                                Gedung
                                @if(request('sort') === 'building')
                                    <span class="text-xs">{{ request('direction') === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('locations.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'floor', 'direction' => (request('sort') === 'floor' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-blue-600">
                                Lantai/Ruang
                                @if(request('sort') === 'floor')
                                    <span class="text-xs">{{ request('direction') === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('locations.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'commodities_count', 'direction' => (request('sort') === 'commodities_count' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-blue-600">
                                Jumlah Barang
                                @if(request('sort') === 'commodities_count')
                                    <span class="text-xs">{{ request('direction') === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $index => $location)
                    <tr>
                        <td class="text-gray-500">{{ $locations->firstItem() + $index }}</td>
                        <td class="font-mono">{{ $location->code }}</td>
                        <td class="font-medium">{{ $location->name }}</td>
                        <td class="text-gray-500">{{ $location->building ?? '-' }}</td>
                        <td class="text-gray-500">{{ $location->floor }} {{ $location->room }}</td>
                        <td>{{ $location->commodities_count }}</td>
                        <td>
                            <div class="flex justify-end gap-1">
                                @can('locations.edit')
                                <button onclick="openEditModal({{ json_encode($location) }})" class="p-1.5 rounded hover:bg-gray-100" title="Edit">
                                    <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endcan

                                @can('locations.delete')
                                <button onclick="deleteLocation({{ $location->id }}, '{{ $location->name }}')" class="p-1.5 rounded hover:bg-red-50" title="Hapus">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <x-empty-state 
                                icon="location"
                                title="Belum Ada Lokasi"
                                description="Tambahkan lokasi untuk mulai mengelola penempatan barang inventaris"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($locations->hasPages() || $locations->count() > 0)
        <div class="card-footer">
            <x-pagination :paginator="$locations" />
        </div>
        @endif
    </div>
    <!-- Create Modal -->
    <x-modal name="createModal" title="Tambah Lokasi Baru" maxWidth="2xl">
        <form id="createForm" action="{{ route('locations.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="createName" class="input w-full" autocomplete="organization" required placeholder="Contoh: Ruang Server Lt.2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Lokasi <span class="text-xs" style="color: var(--text-secondary);">(otomatis jika kosong)</span></label>
                    <input type="text" name="code" id="createCode" class="input w-full" autocomplete="off" placeholder="LOK-001">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">PIC (Person in Charge)</label>
                    <input type="text" name="pic" id="createPic" class="input w-full" autocomplete="name" placeholder="Nama penanggung jawab lokasi">
                </div>
                <input type="hidden" name="is_active" value="1">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="createDescription" class="input w-full" rows="3" autocomplete="off" placeholder="Keterangan tentang lokasi ini..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Alamat Lengkap</label>
                    <textarea name="address" id="createAddress" class="input w-full" rows="3" autocomplete="street-address" placeholder="Jl. Contoh No. 123, Kota..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Modal -->
    <x-modal name="editModal" title="Edit Lokasi" maxWidth="2xl">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" class="input w-full" autocomplete="organization" required placeholder="Nama lokasi">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Lokasi</label>
                    <input type="text" name="code" id="editCode" class="input w-full" autocomplete="off" placeholder="LOK-001">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">PIC (Person in Charge)</label>
                    <input type="text" name="pic" id="editPic" class="input w-full" autocomplete="name" placeholder="Nama penanggung jawab lokasi">
                </div>
                <input type="hidden" name="is_active" value="1">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="editDescription" class="input w-full" rows="3" autocomplete="off" placeholder="Keterangan tentang lokasi ini..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Alamat Lengkap</label>
                    <textarea name="address" id="editAddress" class="input w-full" rows="3" autocomplete="street-address" placeholder="Jl. Contoh No. 123, Kota..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('editModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update
                </button>
            </div>
        </form>
    </x-modal>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: window.innerWidth < 768 ? 'top' : 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            showCloseButton: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

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

        function openCreateModal() {
            document.getElementById('createForm').reset();
            openModal('createModal');
        }

        function openEditModal(location) {
            document.getElementById('editForm').action = `/master/lokasi/${location.id}`;
            document.getElementById('editName').value = location.name || '';
            document.getElementById('editCode').value = location.code || '';
            document.getElementById('editDescription').value = location.description || '';
            document.getElementById('editAddress').value = location.address || '';
            document.getElementById('editPic').value = location.pic || '';
            openModal('editModal');
        }

        async function deleteLocation(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Lokasi?',
                html: `Yakin ingin menghapus <strong>${name}</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
            
            if (!result.isConfirmed) return;
            
            try {
                const response = await fetch(`/master/lokasi/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success !== false) {
                    Toast.fire({ icon: 'success', title: 'Lokasi berhasil dihapus!' });
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Gagal menghapus lokasi');
                }
            } catch (error) {
                console.error('Delete error:', error);
                Swal.fire({ icon: 'error', title: 'Gagal!', text: error.message || 'Terjadi kesalahan' });
            }
        }

        @if(session()->has('success') && session('success'))
        Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif
    </script>
</x-app-layout>
