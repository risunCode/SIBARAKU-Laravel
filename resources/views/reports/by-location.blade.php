<x-app-layout title="Laporan Berdasarkan Lokasi">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('reports.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Laporan
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Laporan Berdasarkan Lokasi</h2>
        </div>

        <!-- Location Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            @foreach($locations as $location)
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1" style="color: var(--text-primary);">{{ $location->name }}</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">{{ $location->building ?? 'N/A' }}</p>
                        <p class="text-sm font-medium mt-1" style="color: var(--text-primary);">{{ $location->commodities_count }} barang</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: var(--accent-color); opacity: 0.1;">
                        <svg class="w-6 h-6" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Detailed Table -->
        <div class="theme-card rounded-xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Detail Barang per Lokasi</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" style="border-color: var(--border-color);">
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Lokasi</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Gedung</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Jumlah Barang</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Kondisi Baik</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Rusak</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Penanggung Jawab</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations as $location)
                            <tr class="border-b hover:opacity-80 transition-opacity" style="border-color: var(--border-color);">
                                <td class="py-3 px-4 font-medium" style="color: var(--text-primary);">{{ $location->name }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $location->building ?? '-' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $location->commodities_count }}</td>
                                <td class="py-3 px-4 text-green-600">{{ $location->good_condition ?? 0 }}</td>
                                <td class="py-3 px-4 text-red-600">{{ $location->damaged_condition ?? 0 }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $location->person_in_charge ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
