<x-app-layout title="Laporan Berdasarkan Kategori">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('reports.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Laporan
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Laporan Berdasarkan Kategori</h2>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            @foreach($categories as $category)
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1" style="color: var(--text-primary);">{{ $category->name }}</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">{{ $category->commodities_count }} barang</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: var(--accent-color); opacity: 0.1;">
                        <svg class="w-6 h-6" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t" style="border-color: var(--border-color);">
                    <div class="flex justify-between text-sm">
                        <span style="color: var(--text-secondary);">Nilai Total:</span>
                        <span class="font-medium" style="color: var(--text-primary);">Rp {{ number_format($category->total_value ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Detailed Table -->
        <div class="theme-card rounded-xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Detail Barang per Kategori</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" style="border-color: var(--border-color);">
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Kategori</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Jumlah Barang</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Kondisi Baik</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Rusak Ringan</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Rusak Berat</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Nilai Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr class="border-b hover:opacity-80 transition-opacity" style="border-color: var(--border-color);">
                                <td class="py-3 px-4 font-medium" style="color: var(--text-primary);">{{ $category->name }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $category->commodities_count }}</td>
                                <td class="py-3 px-4 text-green-600">{{ $category->good_condition ?? 0 }}</td>
                                <td class="py-3 px-4 text-yellow-600">{{ $category->light_damage ?? 0 }}</td>
                                <td class="py-3 px-4 text-red-600">{{ $category->heavy_damage ?? 0 }}</td>
                                <td class="py-3 px-4 font-medium" style="color: var(--text-primary);">Rp {{ number_format($category->total_value ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
