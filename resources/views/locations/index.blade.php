<x-app-layout title="Lokasi">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Lokasi</h2>
            <p class="text-sm text-gray-500">Kelola lokasi penyimpanan barang</p>
        </div>

        @can('locations.create')
        <a href="{{ route('locations.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Lokasi
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('locations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-form.input name="search" placeholder="Cari nama/kode..." :value="request('search')" />

                <x-form.select name="building" placeholder="Semua Gedung" :value="request('building')">
                    @foreach($buildings as $building)
                    <option value="{{ $building }}" {{ request('building') == $building ? 'selected' : '' }}>{{ $building }}</option>
                    @endforeach
                </x-form.select>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('locations.index') }}" class="btn btn-outline">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Gedung</th>
                        <th>Lantai/Ruang</th>
                        <th>Jumlah Barang</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                    <tr>
                        <td class="font-mono">{{ $location->code }}</td>
                        <td class="font-medium">{{ $location->name }}</td>
                        <td class="text-gray-500">{{ $location->building ?? '-' }}</td>
                        <td class="text-gray-500">{{ $location->floor }} {{ $location->room }}</td>
                        <td>{{ $location->commodities_count }}</td>
                        <td>
                            @if($location->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-gray">Nonaktif</span>
                            @endif
                        </td>
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
                        <td colspan="7" class="text-center text-gray-500 py-8">Belum ada data lokasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($locations->hasPages())
        <div class="card-footer">
            <x-pagination :paginator="$locations" />
        </div>
        @endif
    </div>
    <!-- Edit Modal -->
    <div id="editModal-backdrop" class="modal-backdrop"></div>
    <div id="editModal" class="modal-content w-full max-w-md rounded-xl p-6" style="background-color: var(--bg-card);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Edit Lokasi</h3>
            <button onclick="closeModal('editModal')" class="p-1 rounded hover:bg-gray-100">
                <svg class="w-5 h-5" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Nama Lokasi</label>
                    <input type="text" name="name" id="editName" class="input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Kode</label>
                    <input type="text" name="code" id="editCode" class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="editDescription" class="input" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Alamat</label>
                    <textarea name="address" id="editAddress" class="input" rows="2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">PIC</label>
                    <input type="text" name="pic" id="editPic" class="input">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="editIsActive" value="1" class="rounded">
                    <label for="editIsActive" class="text-sm" style="color: var(--text-primary);">Aktif</label>
                </div>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="button" onclick="closeModal('editModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        function openEditModal(location) {
            document.getElementById('editForm').action = `/master/locations/${location.id}`;
            document.getElementById('editName').value = location.name || '';
            document.getElementById('editCode').value = location.code || '';
            document.getElementById('editDescription').value = location.description || '';
            document.getElementById('editAddress').value = location.address || '';
            document.getElementById('editPic').value = location.pic || '';
            document.getElementById('editIsActive').checked = location.is_active;
            openModal('editModal');
        }

        async function deleteLocation(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Lokasi?',
                html: `Yakin ingin menghapus lokasi <strong>${name}</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, hapus!',
            });
            
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/master/locations/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success !== false) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Lokasi berhasil dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Gagal menghapus lokasi');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menghapus lokasi',
                    });
                }
            }
        }
    </script>
</x-app-layout>
