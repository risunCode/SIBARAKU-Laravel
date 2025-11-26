<x-app-layout title="Laporan">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Laporan & Cetak</h1>
            <p style="color: var(--text-secondary);">Pilih jenis laporan untuk ditampilkan atau dicetak</p>
        </div>
        <div class="flex items-center gap-2 text-sm" style="color: var(--text-secondary);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Semua laporan mendukung export PDF & cetak
        </div>
    </div>

    <!-- Laporan Utama -->
    <div class="mb-8">
        <h2 class="text-sm font-semibold uppercase tracking-wider mb-4" style="color: var(--text-secondary);">Laporan Utama</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('reports.inventory') }}" class="group rounded-xl border p-5 hover:shadow-lg transition-all" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: var(--accent-color); opacity: 0.15;">
                        <svg class="w-6 h-6" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                <h3 class="font-semibold mb-1" style="color: var(--text-primary);">Laporan Inventaris</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Daftar lengkap semua barang</p>
            </a>

            <a href="{{ route('reports.by-category') }}" class="group rounded-xl border p-5 hover:shadow-lg transition-all" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                <h3 class="font-semibold mb-1" style="color: var(--text-primary);">Per Kategori</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Rekapitulasi berdasarkan kategori</p>
            </a>

            <a href="{{ route('reports.by-location') }}" class="group rounded-xl border p-5 hover:shadow-lg transition-all" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-orange-100">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                <h3 class="font-semibold mb-1" style="color: var(--text-primary);">Per Lokasi</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Rekapitulasi berdasarkan lokasi</p>
            </a>

            <a href="{{ route('reports.by-condition') }}" class="group rounded-xl border p-5 hover:shadow-lg transition-all" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                <h3 class="font-semibold mb-1" style="color: var(--text-primary);">Per Kondisi</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Rekapitulasi berdasarkan kondisi</p>
            </a>
        </div>
    </div>

    <!-- Laporan Aktivitas -->
    <div class="mb-8">
        <h2 class="text-sm font-semibold uppercase tracking-wider mb-4" style="color: var(--text-secondary);">Laporan Aktivitas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('reports.transfers') }}" class="group rounded-xl border p-5 hover:shadow-lg transition-all" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                <h3 class="font-semibold mb-1" style="color: var(--text-primary);">Laporan Transfer</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Riwayat transfer barang antar lokasi</p>
            </a>

            <a href="{{ route('reports.disposals') }}" class="group rounded-xl border p-5 hover:shadow-lg transition-all" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                <h3 class="font-semibold mb-1" style="color: var(--text-primary);">Laporan Penghapusan</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Riwayat penghapusan barang</p>
            </a>

            <a href="{{ route('reports.maintenance') }}" class="group rounded-xl border p-5 hover:shadow-lg transition-all" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                <h3 class="font-semibold mb-1" style="color: var(--text-primary);">Laporan Maintenance</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Riwayat pemeliharaan barang</p>
            </a>
        </div>
    </div>

    <!-- Quick Tips -->
    <div class="rounded-xl border p-5" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <h3 class="font-semibold mb-3 flex items-center gap-2" style="color: var(--text-primary);">
            <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            Tips Penggunaan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm" style="color: var(--text-secondary);">
            <div class="flex items-start gap-2">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0" style="background-color: var(--accent-color); color: white;">1</span>
                <p>Pilih jenis laporan sesuai kebutuhan analisis data Anda</p>
            </div>
            <div class="flex items-start gap-2">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0" style="background-color: var(--accent-color); color: white;">2</span>
                <p>Gunakan filter tanggal untuk laporan periode tertentu</p>
            </div>
            <div class="flex items-start gap-2">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0" style="background-color: var(--accent-color); color: white;">3</span>
                <p>Export ke PDF atau langsung cetak dari halaman laporan</p>
            </div>
        </div>
    </div>
</x-app-layout>
