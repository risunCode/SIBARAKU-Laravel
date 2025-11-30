@extends('layouts.guest')

@section('title', 'Verifikasi Laporan Digital')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md border border-gray-200">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-6">
                <div class="text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-8 w-auto" onerror="this.style.display='none'">
                    </div>
                    <h1 class="text-xl font-bold text-gray-900 mb-1">VERIFIKASI LAPORAN DIGITAL</h1>
                    <p class="text-sm text-gray-600">Sistem Inventaris Barang</p>
                    <p class="text-xs text-gray-500">Dinas Perumahan dan Kawasan Permukiman</p>
                </div>
            </div>

            <!-- Form -->
            <div class="px-6 py-8">
                <form action="{{ route('report.check') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="hash" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Laporan / Hash Verifikasi
                        </label>
                        <textarea 
                            name="hash" 
                            id="hash" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none font-mono text-sm"
                            placeholder="Masukkan ID laporan atau hash verifikasi..."
                            required
                        >{{ old('hash') }}</textarea>
                        @error('hash')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-3 px-4 font-medium transition-colors">
                        VERIFIKASI LAPORAN
                    </button>
                </form>

                <!-- Info -->
                <div class="mt-6 bg-gray-50 border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 text-sm">PETUNJUK VERIFIKASI:</h3>
                    <ol class="text-xs text-gray-600 space-y-1 list-decimal list-inside">
                        <li>Ambil ID laporan dari footer laporan digital</li>
                        <li>Atau scan QR code jika tersedia</li>
                        <li>Masukkan ID ke form di atas</li>
                        <li>Tekan tombol "Verifikasi Laporan"</li>
                    </ol>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
