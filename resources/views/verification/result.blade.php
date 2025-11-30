@extends('layouts.guest')

@section('title', 'Hasil Verifikasi Laporan Digital')

@section('content')
<div class="min-h-screen bg-white py-6 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="border border-gray-300 px-6 py-4 mb-4">
            <div class="text-center">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-8 w-auto" onerror="this.style.display='none'">
                </div>
                <h1 class="text-xl font-bold mb-1">HASIL VERIFIKASI LAPORAN</h1>
                <p class="text-sm">Sistem Inventaris Barang</p>
                <div class="mt-3">
                    @if($status === 'valid')
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 border border-green-400 text-green-800">
                            <span class="font-semibold">✓ LAPORAN VALID</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 border border-red-400 text-red-800">
                            <span class="font-semibold">✗ LAPORAN TIDAK VALID</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @if($status === 'valid')
                <!-- Left Column - Report Details -->
                <div class="lg:col-span-2">
                    <div class="border border-gray-300 p-4">
                        <h3 class="text-sm font-bold mb-4 uppercase tracking-wide">INFORMASI LAPORAN</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border border-gray-200 p-3">
                                <div class="text-xs text-gray-600 mb-1">Jenis Laporan</div>
                                <div class="font-medium text-sm">
                                    @switch($signature->signable_type)
                                        @case('disposal')
                                            Laporan Penghapusan Barang
                                            @break
                                        @case('maintenance')
                                            Laporan Pemeliharaan Barang
                                            @break
                                        @case('transfer')
                                            Laporan Pemindahan Barang
                                            @break
                                        @default
                                            Laporan Lainnya
                                    @endswitch
                                </div>
                            </div>
                            <div class="border border-gray-200 p-3">
                                <div class="text-xs text-gray-600 mb-1">ID Laporan</div>
                                <div class="font-medium text-sm">#{{ $signature->signable_id }}</div>
                            </div>
                            <div class="border border-gray-200 p-3">
                                <div class="text-xs text-gray-600 mb-1">Dibuat oleh</div>
                                <div class="font-medium text-sm">{{ $signature->user?->name ?? 'Tidak diketahui' }}</div>
                            </div>
                            <div class="border border-gray-200 p-3">
                                <div class="text-xs text-gray-600 mb-1">Waktu Dibuat</div>
                                <div class="font-medium text-sm">{{ $signature->signed_at?->format('d F Y, H:i:s') ?? 'Tidak diketahui' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Signature Details -->
                <div class="lg:col-span-1">
                    <div class="border border-gray-300 p-4">
                        <h3 class="text-base font-semibold mb-3">Detail Tanda Tangan Digital</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-xs text-gray-600 mb-1">ID Verifikasi</div>
                                <code class="block text-xs font-mono bg-gray-50 p-2 border border-gray-200 word-break">{{ $signature->signature_hash }}</code>
                            </div>
                            <div class="text-xs text-gray-500 space-y-1 pt-3 border-t border-gray-200">
                                <p>✓ Laporan ini telah diverifikasi menggunakan teknologi hash SHA-256</p>
                                <p>✓ Integritas laporan terjamin dan tidak dapat diubah</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Invalid Report - Full Width -->
                <div class="lg:col-span-3">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-red-800 mb-2">{{ $message }}</h2>
                        <p class="text-gray-600 mb-6 text-sm">Laporan ini tidak dapat diverifikasi</p>

                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-left max-w-xl mx-auto">
                            <h3 class="font-semibold text-red-800 mb-3 text-sm">Kemungkinan Penyebab:</h3>
                            <ul class="text-sm text-red-700 space-y-2">
                                <li>• Laporan telah dimodifikasi atau diubah</li>
                                <li>• ID verifikasi tidak valid atau expired</li>
                                <li>• Link verifikasi rusak atau tidak lengkap</li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-gray-300 pt-4">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-3">ID Verifikasi yang Anda masukkan:</p>
                            <code class="text-xs font-mono bg-gray-100 p-2 border border-gray-300 word-break">{{ $hash }}</code>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions - Full Width -->
        <div class="border-t border-gray-300 pt-4 mt-6 text-center">
            <a href="{{ route('report.verification') }}" class="inline-block bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 font-medium transition-colors text-sm">
                VERIFIKASI LAPORAN LAIN
            </a>
        </div>
    </div>
</div>

<style>
.word-break {
    word-break: break-all;
}
</style>
@endsection
