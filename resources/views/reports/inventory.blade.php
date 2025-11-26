<x-app-layout title="Laporan Inventaris">
    <div class="mb-6">
        <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <h2 class="text-xl font-bold text-gray-900 mt-2">Laporan Inventaris</h2>
    </div>

    <!-- Filter Form -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Filter Laporan</h3>
        </div>
        <form action="{{ route('reports.inventory') }}" method="GET">
            <div class="card-body grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-form.select label="Kategori" name="category_id" :value="request('category_id')">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select label="Lokasi" name="location_id" :value="request('location_id')">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select label="Kondisi" name="condition" :value="request('condition')" :options="['' => 'Semua Kondisi', 'baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat']" />

                <x-form.input label="Tahun Perolehan" name="year" type="number" :value="request('year')" placeholder="Contoh: 2024" />
            </div>
            <div class="card-footer flex justify-end gap-3">
                <a href="{{ route('reports.inventory') }}" class="btn btn-outline">Reset</a>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
                <button type="submit" name="export" value="pdf" class="btn btn-success">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-2xl font-bold text-primary-600">{{ number_format($commodities->count()) }}</p>
                <p class="text-sm text-gray-500">Total Barang</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-2xl font-bold text-success-600">{{ number_format($commodities->where('condition', 'baik')->count()) }}</p>
                <p class="text-sm text-gray-500">Kondisi Baik</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-2xl font-bold text-warning-600">{{ number_format($commodities->where('condition', 'rusak_ringan')->count()) }}</p>
                <p class="text-sm text-gray-500">Rusak Ringan</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-2xl font-bold text-danger-600">{{ number_format($commodities->where('condition', 'rusak_berat')->count()) }}</p>
                <p class="text-sm text-gray-500">Rusak Berat</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th class="text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commodities as $i => $commodity)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="font-mono text-xs">{{ $commodity->item_code }}</td>
                        <td>{{ $commodity->name }}</td>
                        <td>{{ $commodity->category->name ?? '-' }}</td>
                        <td>{{ $commodity->location->name ?? '-' }}</td>
                        <td><span class="badge {{ $commodity->condition_badge_class }}">{{ $commodity->condition_label }}</span></td>
                        <td class="text-right">{{ $commodity->formatted_price }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-8">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($commodities->count() > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="6" class="font-semibold text-right">Total Nilai:</td>
                        <td class="font-semibold text-right">Rp {{ number_format($commodities->sum('purchase_price'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-app-layout>
