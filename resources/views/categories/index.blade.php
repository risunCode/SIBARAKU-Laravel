@section('meta-description', 'Kelola kategori barang inventaris dengan sistem hierarki. Organisir aset perusahaan berdasarkan jenis dan fungsi untuk tracking yang lebih efisien.')
<x-app-layout title="Kategori">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Kategori</h2>
            <p class="text-sm text-gray-600">Kelola kategori barang inventaris</p>
        </div>

        <div class="flex gap-2">
            <!-- Filter Toggle -->
            <div class="flex items-center gap-2">
                <button onclick="toggleParentCategories()" id="toggleParentBtn" class="btn btn-outline" title="Filter kategori induk">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span id="toggleText">🏷️ Sembunyikan Kategori Utama</span>
                </button>
            </div>

            @can('categories.create')
            <button onclick="openCreateModal()" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kategori
            </button>
            @endcan
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form id="filterForm" action="{{ route('categories.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4" data-no-warn>
                <div class="relative">
                    <input type="text" 
                        name="search" 
                        id="searchInput"
                        class="input w-full pl-10" 
                        placeholder="Cari nama atau kode..." 
                        value="{{ request('search') }}"
                        oninput="debounceSearch()"
                    >
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <select name="parent_id" class="input w-full" onchange="submitFilter()">
                    <option value="">Semua Kategori</option>
                    <option value="root" {{ request('parent_id') === 'root' ? 'selected' : '' }}>🏷️ Hanya Kategori Utama</option>
                    <optgroup label="📂 Subkategori dari:">
                        @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                            └─ {{ $parent->name }}
                        </option>
                        @endforeach
                    </optgroup>
                </select>

                <select name="sort" class="input w-full" onchange="submitFilter()">
                    <option value="code" {{ request('sort', 'code') === 'code' ? 'selected' : '' }}>Urutkan: Kode</option>
                    <option value="parent_name" {{ request('sort') === 'parent_name' ? 'selected' : '' }}>Urutkan: Induk</option>
                    <option value="commodities_count" {{ request('sort') === 'commodities_count' ? 'selected' : '' }}>Urutkan: Jumlah</option>
                </select>

                <div class="flex gap-2">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline flex-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </a>
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
                        <th class="w-12">No</th>
                        <th>
                            <a href="{{ route('categories.index', array_merge(request()->query(), ['sort' => 'code', 'direction' => request('sort') == 'code' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                                Kode
                                @if(request('sort') == 'code')
                                    @if(request('direction') == 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('categories.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                                Nama Kategori
                                @if(request('sort') == 'name')
                                    @if(request('direction') == 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('categories.index', array_merge(request()->query(), ['sort' => 'parent_name', 'direction' => request('sort') == 'parent_name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                                Induk Kategori
                                @if(request('sort') == 'parent_name')
                                    @if(request('direction') == 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('categories.index', array_merge(request()->query(), ['sort' => 'commodities_count', 'direction' => request('sort') == 'commodities_count' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                                Jumlah Barang
                                @if(request('sort') == 'commodities_count')
                                    @if(request('direction') == 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr class="{{ $category->parent_id ? 'bg-gray-50/50' : 'bg-blue-50/30' }}">
                        <td class="text-gray-500">{{ $categories->firstItem() + $index }}</td>
                        <td class="font-mono">
                            <span class="{{ $category->parent_id ? 'text-gray-600' : 'text-blue-600 font-semibold' }}">
                                {{ $category->code }}
                            </span>
                        </td>
                        <td class="font-medium">
                            @if($category->parent_id)
                                {{-- Subkategori --}}
                                <div class="flex items-center pl-4">
                                    <span class="text-gray-400 mr-2">└─</span>
                                    <span class="text-gray-700">{{ $category->name }}</span>
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-600">📂 Sub</span>
                                </div>
                            @else
                                {{-- Kategori Utama --}}
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    <strong class="text-gray-900">{{ $category->name }}</strong>
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">🏷️ Utama</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($category->parent_id)
                                <span class="text-sm text-gray-600">
                                    <span class="text-gray-400">dari:</span> {{ $category->parent->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm italic">— Kategori Utama —</span>
                            @endif
                        </td>
                        <td>{{ $category->commodities_count }}</td>
                        <td>
                            <div class="flex justify-end gap-1">
                                @can('categories.edit')
                                <button onclick="openEditModal({{ json_encode($category) }})" class="p-1.5 rounded hover:bg-gray-100" title="Edit">
                                    <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endcan

                                @can('categories.delete')
                                <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" class="p-1.5 rounded hover:bg-red-50" title="Hapus">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <x-empty-state 
                                icon="category"
                                title="Belum Ada Kategori"
                                description="Buat kategori untuk mengorganisir barang inventaris dengan lebih baik"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages() || $categories->count() > 0)
        <div class="card-footer">
            <x-pagination :paginator="$categories" />
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <x-modal name="createModal" title="Tambah Kategori Baru" maxWidth="2xl">
        <form id="createForm" action="{{ route('categories.store') }}" method="POST" onsubmit="return validateCategoryForm()">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Kategori -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="createName" class="input w-full" autocomplete="organization" required placeholder="Contoh: Elektronik, Laptop, Kantor">
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">Masukkan nama kategori yang jelas dan spesifik</p>
                </div>
                
                <!-- Kode Kategori Dropdown dengan Grouping -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Kategori</label>
                    <select name="code" id="createCode" class="input w-full" onchange="toggleNewCodeField()">
                        <option value="">-- Pilih Kode --</option>
                        
                        <!-- Perlengkapan IT Group -->
                        <optgroup label="🖥️ Perlengkapan IT">
                            <option value="TIK">TIK - Peralatan IT</option>
                            <option value="ELK">ELK - Elektronik Kantor</option>
                        </optgroup>
                        
                        <!-- Alat Tulis Group -->
                        <optgroup label="📝 Alat Tulis & Kantor">
                            <option value="ATK">ATK - Alat Tulis Kantor</option>
                        </optgroup>
                        
                        <!-- Kendaraan Group -->
                        <optgroup label="🚗 Kendaraan & Operasional">
                            <option value="KMP">KMP - Kendaraan Operasional</option>
                        </optgroup>
                        
                        <!-- Rumah Tangga Group -->
                        <optgroup label="🏠 Peralatan Penunjang">
                            <option value="PRT">PRT - Peralatan Rumah Tangga</option>
                        </optgroup>
                        
                        <!-- Other Codes Group (Dynamic) -->
                        @php
                            $groupedCodes = ['TIK', 'ELK', 'ATK', 'KMP', 'PRT'];
                            $otherCodes = $existingCodes->diff($groupedCodes);
                        @endphp
                        @if($otherCodes->isNotEmpty())
                        <optgroup label="📋 Kode Lainnya">
                            @foreach($otherCodes as $otherCode)
                            <option value="{{ $otherCode }}">{{ $otherCode }}</option>
                            @endforeach
                        </optgroup>
                        @endif
                        
                        <option value="new">+ Buat Kode Baru</option>
                    </select>
                    <div id="newCodeField" class="hidden mt-2">
                        <input type="text" name="new_code" id="newCode" class="input w-full" placeholder="Masukkan kode kategori baru">
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Masukkan kode unik untuk kategori baru</p>
                    </div>
                </div>
                
                <!-- Induk Kategori dengan Opsi Lainnya -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                        Induk Kategori
                        <span class="text-xs font-normal ml-1" style="color: var(--text-secondary);">(opsional)</span>
                    </label>
                    <select name="parent_id" id="createParentId" class="input w-full" onchange="toggleNewParentField()">
                        <option value="">🏷️ Tidak ada — Jadikan Kategori Utama</option>
                        <optgroup label="📂 Pilih Induk Kategori">
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">└─ {{ $parent->name }}</option>
                            @endforeach
                        </optgroup>
                        <option value="new">+ Buat Induk Kategori Baru</option>
                    </select>
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">
                        💡 Kosongkan jika ini adalah kategori utama (bukan subkategori)
                    </p>
                    <div id="newParentField" class="hidden mt-2">
                        <input type="text" name="new_parent_name" id="newParentName" class="input w-full" placeholder="Masukkan nama induk kategori baru">
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Induk kategori baru akan dibuat otomatis</p>
                    </div>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Status</label>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="is_active" id="isActive" checked class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <label for="isActive" class="text-sm" style="color: var(--text-secondary);">Aktif</label>
                    </div>
                </div>
                
                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="createDescription" class="input w-full" rows="3" autocomplete="off" placeholder="Keterangan tentang kategori ini..."></textarea>
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
    <x-modal name="editModal" title="Edit Kategori" maxWidth="2xl">
        <form id="editForm" method="POST" action="/master/kategori">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Kategori -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" class="input w-full" autocomplete="organization" required placeholder="Contoh: Elektronik, Laptop, Kantor">
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">Masukkan nama kategori yang jelas dan spesifik</p>
                </div>
                
                <!-- Kode Kategori -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Kategori</label>
                    <input type="text" name="code" id="editCode" class="input w-full" autocomplete="off" placeholder="Contoh: TIK-LAP">
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">Kode unik untuk identifikasi kategori</p>
                </div>
                
                <!-- Induk Kategori -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                        Induk Kategori
                        <span class="text-xs font-normal ml-1" style="color: var(--text-secondary);">(opsional)</span>
                    </label>
                    <select name="parent_id" id="editParentId" class="input w-full">
                        <option value="">🏷️ Tidak ada — Jadikan Kategori Utama</option>
                        <optgroup label="📂 Pilih Induk Kategori">
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">└─ {{ $parent->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">
                        💡 Kosongkan jika ini adalah kategori utama
                    </p>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Status</label>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="is_active" id="editIsActive" checked class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <label for="editIsActive" class="text-sm" style="color: var(--text-secondary);">Aktif</label>
                    </div>
                </div>
                
                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="editDescription" class="input w-full" rows="3" autocomplete="off" placeholder="Keterangan tentang kategori ini..."></textarea>
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
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

        // Submit filter without triggering "Leave site?" dialog
        function submitFilter() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams();
            
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            
            // Direct navigation instead of form submit (no "Leave site?" dialog)
            window.location.href = form.action + '?' + params.toString();
        }

        // Debounced search - auto submit after 500ms of no typing
        let searchTimeout;
        function debounceSearch() {
            const spinner = document.getElementById('searchSpinner');
            spinner.classList.remove('hidden');
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                submitFilter();
            }, 500);
        }

        function openCreateModal() {
            document.getElementById('createForm').reset();
            openModal('createModal');
        }

        // Toggle parent categories visibility
        function toggleParentCategories() {
            const rows = document.querySelectorAll('tbody tr');
            const toggleBtn = document.getElementById('toggleParentBtn');
            const toggleText = document.getElementById('toggleText');
            let isHidden = toggleBtn.classList.contains('active');
            
            rows.forEach(row => {
                // Check if row has blue background (parent category)
                if (row.classList.contains('bg-blue-50/30')) {
                    row.style.display = isHidden ? '' : 'none';
                }
            });
            
            if (isHidden) {
                toggleBtn.classList.remove('active');
                toggleBtn.classList.remove('btn-primary');
                toggleBtn.classList.add('btn-outline');
                toggleText.textContent = '🏷️ Sembunyikan Kategori Utama';
                Toast.fire({
                    icon: 'info',
                    title: 'Menampilkan kategori utama'
                });
            } else {
                toggleBtn.classList.add('active');
                toggleBtn.classList.remove('btn-outline');
                toggleBtn.classList.add('btn-primary');
                toggleText.textContent = '📂 Tampilkan Kategori Utama';
                Toast.fire({
                    icon: 'info',
                    title: 'Hanya menampilkan subkategori'
                });
            }
        }

        // Toggle new parent field
        function toggleNewParentField() {
            const select = document.getElementById('createParentId');
            const newField = document.getElementById('newParentField');
            const newInput = document.getElementById('newParentName');
            
            if (select.value === 'new') {
                newField.classList.remove('hidden');
                newInput.focus();
            } else {
                newField.classList.add('hidden');
                newInput.value = '';
            }
        }

        // Toggle new code field
        function toggleNewCodeField() {
            const select = document.getElementById('createCode');
            const newField = document.getElementById('newCodeField');
            const newInput = document.getElementById('newCode');
            
            if (select.value === 'new') {
                newField.classList.remove('hidden');
                newInput.focus();
            } else {
                newField.classList.add('hidden');
                newInput.value = '';
            }
        }

        // Validate form before submission
        function validateCategoryForm() {
            const nameInput = document.getElementById('createName');
            const codeSelect = document.getElementById('createCode');
            const newCodeInput = document.getElementById('newCode');
            const parentSelect = document.getElementById('createParentId');
            const newParentInput = document.getElementById('newParentName');
            
            // Check if name is filled
            if (!nameInput.value.trim()) {
                Toast.fire({
                    icon: 'error',
                    title: 'Nama kategori wajib diisi!'
                });
                nameInput.focus();
                return false;
            }
            
            // Check if code is selected
            if (!codeSelect.value) {
                Toast.fire({
                    icon: 'error',
                    title: 'Kode kategori wajib dipilih!'
                });
                codeSelect.focus();
                return false;
            }
            
            // Check if new code is selected but value is empty
            if (codeSelect.value === 'new' && !newCodeInput.value.trim()) {
                Toast.fire({
                    icon: 'error',
                    title: 'Kode kategori baru wajib diisi!'
                });
                newCodeInput.focus();
                return false;
            }
            
            // Check if new parent is selected but name is empty
            if (parentSelect.value === 'new' && !newParentInput.value.trim()) {
                Toast.fire({
                    icon: 'error',
                    title: 'Nama parent kategori baru wajib diisi!'
                });
                newParentInput.focus();
                return false;
            }
            
            return true;
        }

        function openEditModal(category) {
            document.getElementById('editForm').action = `/master/kategori/${category.id}`;
            document.getElementById('editName').value = category.name || '';
            document.getElementById('editCode').value = category.code || '';
            document.getElementById('editDescription').value = category.description || '';
            document.getElementById('editParentId').value = category.parent_id || '';
            document.getElementById('editIsActive').checked = category.is_active == 1 || category.is_active === true;
            openModal('editModal');
        }

        async function deleteCategory(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Kategori?',
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
                const response = await fetch(`/master/kategori/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success !== false) {
                    Toast.fire({ icon: 'success', title: 'Kategori berhasil dihapus!' });
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Gagal menghapus kategori');
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
