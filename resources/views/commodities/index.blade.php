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
            <a href="{{ route('commodities.create') }}" class="btn btn-primary">
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
            <form action="{{ route('commodities.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <x-form.input 
                    name="search" 
                    placeholder="Cari kode/nama/merk..." 
                    :value="request('search')"
                />

                <x-form.select 
                    name="category_id" 
                    placeholder="Semua Kategori"
                    :value="request('category_id')"
                >
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </x-form.select>

                <x-form.select 
                    name="location_id" 
                    placeholder="Semua Lokasi"
                    :value="request('location_id')"
                >
                    @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                    @endforeach
                </x-form.select>

                <x-form.select 
                    name="condition" 
                    placeholder="Semua Kondisi"
                    :value="request('condition')"
                    :options="['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat']"
                />

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('commodities.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Nilai</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commodities as $index => $commodity)
                    <tr>
                        <td class="text-gray-500">{{ $commodities->firstItem() + $index }}</td>
                        <td class="font-mono text-xs">{{ $commodity->item_code }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $commodity->primary_image_url }}" 
                                     class="w-10 h-10 rounded object-cover bg-gray-100 cursor-pointer hover:opacity-80 transition" 
                                     alt="{{ $commodity->name }}"
                                     onclick="viewImage('{{ $commodity->primary_image_url }}', '{{ $commodity->name }}')">
                                <div>
                                    <p class="font-medium text-gray-900">{{ Str::limit($commodity->name, 30) }}</p>
                                    @if($commodity->brand)
                                    <p class="text-xs text-gray-500">{{ $commodity->brand }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-gray-500">{{ $commodity->category->name ?? '-' }}</td>
                        <td class="text-gray-500">{{ $commodity->location->name ?? '-' }}</td>
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
                        <td colspan="8" class="text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p>Belum ada data barang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($commodities->hasPages())
        <div class="card-footer">
            <x-pagination :paginator="$commodities" />
        </div>
        @endif
    </div>

</x-app-layout>
