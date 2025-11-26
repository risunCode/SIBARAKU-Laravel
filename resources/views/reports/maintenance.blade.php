<x-app-layout title="Laporan Maintenance">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('reports.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Laporan
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Laporan Maintenance Barang</h2>
        </div>

        <!-- Maintenance Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $maintenanceStats['total'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Total Maintenance</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $maintenanceStats['this_month'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Bulan Ini</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-orange-600">{{ $maintenanceStats['overdue'] ?? 0 }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Terlambat</p>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($maintenanceStats['total_cost'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Total Biaya</p>
                </div>
            </div>
        </div>

        <!-- Recent Maintenance -->
        <div class="theme-card rounded-xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Riwayat Maintenance Terbaru</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" style="border-color: var(--border-color);">
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Barang</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Jenis Maintenance</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Tanggal</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Biaya</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Teknisi</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Maintenance Selanjutnya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($maintenanceLogs ?? [] as $maintenance)
                            <tr class="border-b hover:opacity-80 transition-opacity" style="border-color: var(--border-color);">
                                <td class="py-3 px-4 font-medium" style="color: var(--text-primary);">{{ $maintenance->commodity->name ?? '-' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $maintenance->maintenance_type ?? 'Maintenance' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $maintenance->maintenance_date->format('d/m/Y') }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">
                                    @if($maintenance->cost > 0)
                                        Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $maintenance->technician ?? '-' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">
                                    @if($maintenance->next_maintenance)
                                        {{ $maintenance->next_maintenance->format('d/m/Y') }}
                                        @if($maintenance->next_maintenance->isPast())
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Terlambat</span>
                                        @elseif($maintenance->next_maintenance->isToday() || $maintenance->next_maintenance->isTomorrow())
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Segera</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center" style="color: var(--text-secondary);">Belum ada data maintenance</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Maintenance Schedule -->
        <div class="mt-6 theme-card rounded-xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Jadwal Maintenance Mendatang</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" style="border-color: var(--border-color);">
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Barang</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Lokasi</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Jadwal</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Sisa Hari</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingMaintenance ?? [] as $schedule)
                            <tr class="border-b hover:opacity-80 transition-opacity" style="border-color: var(--border-color);">
                                <td class="py-3 px-4 font-medium" style="color: var(--text-primary);">{{ $schedule->commodity->name ?? '-' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $schedule->commodity->location->name ?? '-' }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $schedule->next_maintenance->format('d/m/Y') }}</td>
                                <td class="py-3 px-4" style="color: var(--text-secondary);">{{ $schedule->next_maintenance->diffForHumans() }}</td>
                                <td class="py-3 px-4">
                                    @if($schedule->next_maintenance->isPast())
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Terlambat</span>
                                    @elseif($schedule->next_maintenance->diffInDays() <= 7)
                                        <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Segera</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Normal</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center" style="color: var(--text-secondary);">Tidak ada jadwal maintenance</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
