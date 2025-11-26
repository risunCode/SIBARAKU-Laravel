<x-app-layout title="Pengguna">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold" style="color: var(--text-primary);">Kelola Pengguna</h1>
            <p class="text-sm" style="color: var(--text-secondary);">Admin/Super Admin: {{ $adminCount }}/3 (Staff: unlimited)</p>
        </div>

        <div class="flex gap-2">
            @can('users.manage')
            <a href="{{ route('referral-codes.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Kode Referral
            </a>
            @endcan
            @can('users.create')
            <button onclick="openCreateModal()" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pengguna
            </button>
            @endcan
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <x-form.input name="search" placeholder="Cari nama/email/kode referral..." :value="request('search')" />

                <x-form.select name="role" placeholder="Semua Role" :value="request('role')">
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select name="status" placeholder="Semua Status" :value="request('status')" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" />

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>Pengguna</th>
                        <th>Kode Referral</th>
                        <th>Direferensikan Oleh</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $user->name }}">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $user->referral_code }}</code>
                                <button type="button" 
                                        onclick="copyToClipboard('{{ url('register?ref=' . $user->referral_code) }}')"
                                        class="text-gray-400 hover:text-primary-600" title="Salin Link Referral">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="text-gray-500">
                            {{ $user->referrer?->name ?? '-' }}
                        </td>
                        <td>
                            @foreach($user->roles as $role)
                            <span class="badge {{ $role->name === 'super-admin' ? 'badge-danger' : ($role->name === 'admin' ? 'badge-warning' : 'badge-info') }}">
                                {{ ucfirst($role->name) }}
                            </span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-gray">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('users.show', $user) }}" class="p-1.5 rounded hover:bg-gray-100" title="Detail">
                                    <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                @can('users.edit')
                                <button onclick="openEditModal({{ json_encode($user->only(['id', 'name', 'email', 'phone', 'is_active']) + ['role' => $user->roles->first()?->name]) }})" class="p-1.5 rounded hover:bg-gray-100" title="Edit">
                                    <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endcan

                                @can('users.delete')
                                @if($user->id !== auth()->id())
                                <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" class="p-1.5 rounded hover:bg-red-50" title="Hapus">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-8">Belum ada data pengguna</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="card-footer">
            <x-pagination :paginator="$users" />
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <div id="createModal-backdrop" class="modal-backdrop"></div>
    <div id="createModal" class="modal-content w-full max-w-md rounded-xl p-6" style="background-color: var(--bg-card);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Tambah Pengguna</h3>
            <button onclick="closeModal('createModal')" class="p-1 rounded hover:bg-gray-100">
                <svg class="w-5 h-5" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="createForm" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Nama</label>
                    <input type="text" name="name" id="createName" class="input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Email</label>
                    <input type="email" name="email" id="createEmail" class="input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">No. Telepon</label>
                    <input type="text" name="phone" id="createPhone" class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Password</label>
                    <input type="password" name="password" id="createPassword" class="input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="createPasswordConfirmation" class="input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Role</label>
                    <select name="role" id="createRole" class="input" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Kode Referral <span class="text-xs text-gray-500">(opsional)</span></label>
                    <input type="text" name="referral_code" id="createReferralCode" class="input" placeholder="Masukkan kode referral">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="createIsActive" value="1" class="rounded" checked>
                    <label for="createIsActive" class="text-sm" style="color: var(--text-primary);">Aktif</label>
                </div>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Edit Modal -->
    <div id="editModal-backdrop" class="modal-backdrop"></div>
    <div id="editModal" class="modal-content w-full max-w-md rounded-xl p-6" style="background-color: var(--bg-card);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Edit Pengguna</h3>
            <button onclick="closeModal('editModal')" class="p-1 rounded hover:bg-gray-100">
                <svg class="w-5 h-5" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Nama</label>
                    <input type="text" name="name" id="editName" class="input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Email</label>
                    <input type="email" name="email" id="editEmail" class="input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">No. Telepon</label>
                    <input type="text" name="phone" id="editPhone" class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Role</label>
                    <select name="role" id="editRole" class="input">
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
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
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersalin!',
                    text: 'Link referral berhasil disalin',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        function openCreateModal() {
            document.getElementById('createForm').reset();
            document.getElementById('createIsActive').checked = true;
            openModal('createModal');
        }

        function openEditModal(user) {
            document.getElementById('editForm').action = `/admin/users/${user.id}`;
            document.getElementById('editName').value = user.name || '';
            document.getElementById('editEmail').value = user.email || '';
            document.getElementById('editPhone').value = user.phone || '';
            document.getElementById('editRole').value = user.role || '';
            document.getElementById('editIsActive').checked = user.is_active;
            openModal('editModal');
        }

        async function deleteUser(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Pengguna?',
                html: `Yakin ingin menghapus pengguna <strong>${name}</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, hapus!',
            });
            
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${id}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
