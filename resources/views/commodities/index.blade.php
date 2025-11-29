@section('meta-description', 'Daftar lengkap barang inventaris dengan detail kategori, lokasi, kondisi, dan tracking perpindahan. Kelola aset perusahaan dengan mudah.')
<x-app-layout title="Daftar Barang">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Barang</h2>
            <p class="text-sm text-gray-500">Kelola semua barang inventaris</p>
        </div>

        <div class="flex gap-2">
            @can('commodities.export')
            <a href="{{ route('commodities.export', request()->query()) }}" class="btn btn-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </a>
            @endcan

            @can('commodities.create')
            <a href="{{ route('commodities.create') }}" class="btn btn-primary" title="Tambah Barang Baru (Ctrl+N)">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Barang
            </a>
            @endcan
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form id="filterForm" action="{{ route('commodities.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4" data-no-warn>
                <div class="relative">
                    <input type="text" name="search" id="searchInput" class="input w-full pl-10 pr-10" placeholder="Cari kode/nama/merk..." value="{{ request('search') }}" oninput="debounceSearch()">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    @if(request('search'))
                    <button type="button" onclick="clearSearch()" class="absolute right-10 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" title="Clear search">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    @endif
                    <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                <select name="category_id" class="input w-full" onchange="submitFilter()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>

                <select name="location_id" class="input w-full" onchange="submitFilter()">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                    @endforeach
                </select>

                <select name="condition" class="input w-full" onchange="submitFilter()">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('condition') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('condition') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>

                <a href="{{ route('commodities.index') }}" class="btn btn-outline">Reset</a>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th class="w-24 font-mono">Kode</th>
                        <th class="min-w-[200px]">Nama Barang</th>
                        <th class="w-32">Kategori</th>
                        <th class="w-32">Lokasi</th>
                        <th class="w-28">Kondisi</th>
                        <th class="w-32">Nilai</th>
                        <th class="w-36 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commodities as $index => $commodity)
                    <tr>
                        <td class="text-gray-500 text-center">{{ $commodities->firstItem() + $index }}</td>
                        <td class="font-mono text-xs">{{ $commodity->item_code }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $commodity->primary_image_url }}" 
                                     class="w-10 h-10 rounded object-cover bg-gray-100 cursor-pointer hover:opacity-80 transition" 
                                     alt="{{ $commodity->name }}"
                                     onclick="viewImage('{{ $commodity->primary_image_url }}', '{{ $commodity->name }}')">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate max-w-xs" title="{{ $commodity->name }}">{{ $commodity->name }}</p>
                                    @if($commodity->brand)
                                    <p class="text-xs text-gray-500 truncate" title="{{ $commodity->brand }}">{{ $commodity->brand }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-gray-500 truncate max-w-[120px]" title="{{ $commodity->category->name ?? '-' }}">{{ $commodity->category->name ?? '-' }}</td>
                        <td class="text-gray-500 truncate max-w-[120px]" title="{{ $commodity->location->name ?? '-' }}">{{ $commodity->location->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $commodity->condition_badge_class }}">
                                {{ $commodity->condition_label }}
                            </span>
                        </td>
                        <td class="font-medium">{{ $commodity->formatted_price }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('commodities.show', $commodity) }}" class="btn btn-sm btn-outline">
                                    Detail
                                </a>

                                @can('commodities.edit')
                                <a href="{{ route('commodities.edit', $commodity) }}" class="btn btn-sm btn-outline">
                                    Edit
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <x-empty-state 
                                icon="box"
                                title="Belum ada barang"
                                description="Mulai dengan menambahkan barang inventaris pertama Anda"
                                action="{{ route('commodities.create') }}"
                                actionText="Tambah Barang"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($commodities->hasPages() || $commodities->count() > 0)
        <div class="card-footer">
            <x-pagination :paginator="$commodities" />
        </div>
        @endif
    </div>

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

        function clearSearch() {
            const input = document.getElementById('searchInput');
            if (input) {
                input.value = '';
                input.focus();
                submitFilter();
            }
        }
    </script>
</x-app-layout>
