<x-app-layout title="Laporan Penghapusan">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('reports.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Laporan
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Laporan Penghapusan Barang</h2>
        </div>

        <!-- Disposal Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $disposalStats['total'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Total Pengajuan</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $disposalStats['pending'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Pending</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $disposalStats['approved'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Disetujui</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $disposalStats['rejected'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Ditolak</p>
                </div>
            </div>
        </div>

        <!-- Recent Disposals -->
        <div class="theme-card rounded-xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Riwayat Penghapusan Terbaru</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" style="border-color: var(--border-color);">
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">No. Disposal</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Barang</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Alasan</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Nilai Taksiran</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Status</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Tanggal</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Pengaju</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disposals ?? [] as $disposal)
                            <tr class="border-b hover:opacity-80 transition-opacity" style="border-color: var(--border-color);">
                                <td class="py-3 px-4 font-medium" style="color: var(--text-primary);">{{ $disposal->disposal_number }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $disposal->commodity->name ?? '-' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">
                                    @switch($disposal->reason)
                                        @case('rusak_berat') Rusak Berat @break
                                        @case('hilang') Hilang @break
                                        @case('usang') Usang @break
                                        @case('dicuri') Dicuri @break
                                        @case('dijual') Dijual @break
                                        @case('dihibahkan') Dihibahkan @break
                                        @default {{ ucfirst($disposal->reason) }}
                                    @endswitch
                                </td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">
                                    Rp {{ number_format($disposal->estimated_value ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4">
                                    @switch($disposal->status)
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @break
                                        @case('approved')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Disetujui</span>
                                            @break
                                        @case('rejected')
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Selesai</span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ ucfirst($disposal->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $disposal->created_at->format('d/m/Y') }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $disposal->requester->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center" style="color: var(--text-secondary);">Belum ada data penghapusan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
