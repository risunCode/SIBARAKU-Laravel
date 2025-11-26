<x-app-layout title="Laporan Transfer">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('reports.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Laporan
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Laporan Transfer Barang</h2>
        </div>

        <!-- Transfer Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $transferStats['total'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Total Transfer</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $transferStats['pending'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Pending</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $transferStats['approved'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Disetujui</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $transferStats['completed'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Selesai</p>
                </div>
            </div>
        </div>

        <!-- Recent Transfers -->
        <div class="theme-card rounded-xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Riwayat Transfer Terbaru</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" style="border-color: var(--border-color);">
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">No. Transfer</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Barang</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Dari</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Ke</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Status</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Tanggal</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Pengaju</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfers ?? [] as $transfer)
                            <tr class="border-b hover:opacity-80 transition-opacity" style="border-color: var(--border-color);">
                                <td class="py-3 px-4 font-medium" style="color: var(--text-primary);">{{ $transfer->transfer_number }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $transfer->commodity->name ?? '-' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $transfer->fromLocation->name ?? '-' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $transfer->toLocation->name ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    @switch($transfer->status)
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @break
                                        @case('approved')
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Disetujui</span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Selesai</span>
                                            @break
                                        @case('rejected')
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ ucfirst($transfer->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $transfer->created_at->format('d/m/Y') }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $transfer->requester->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center" style="color: var(--text-secondary);">Belum ada data transfer</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
