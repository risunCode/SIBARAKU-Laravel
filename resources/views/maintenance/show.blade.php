<x-app-layout title="Detail Maintenance">
    <div class="mb-6">
        <a href="{{ route('maintenance.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <h2 class="text-xl font-bold text-gray-900 mt-2">Detail Maintenance</h2>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Left Column (2/3) -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Maintenance Details -->
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Informasi Maintenance</h3>
                    @can('maintenance.edit')
                    <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn btn-sm btn-outline">Edit</a>
                    @endcan
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Jenis Maintenance</dt>
                            <dd class="font-medium">{{ $maintenance->maintenance_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Tanggal</dt>
                            <dd class="font-medium">{{ $maintenance->maintenance_date->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Biaya</dt>
                            <dd class="font-medium">{{ $maintenance->formatted_cost }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Kondisi Setelah</dt>
                            <dd><span class="badge {{ $maintenance->condition_after === 'baik' ? 'badge-success' : 'badge-warning' }}">{{ $maintenance->condition_after_label }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Dilakukan Oleh</dt>
                            <dd class="font-medium">{{ $maintenance->performed_by ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Jadwal Berikutnya</dt>
                            <dd class="font-medium">
                                @if($maintenance->next_maintenance_date)
                                    {{ $maintenance->next_maintenance_date->format('d M Y') }}
                                    @if($maintenance->isOverdue())
                                    <span class="badge badge-danger ml-2">Terlambat</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                    </dl>

                    @if($maintenance->description)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-2">Deskripsi Pekerjaan:</p>
                        <p class="whitespace-pre-line">{{ $maintenance->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column (1/3) -->
        <div class="space-y-6">
            <!-- Commodity Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Barang</h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center gap-4">
                        <img src="{{ $maintenance->commodity->primary_image_url }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                        <div>
                            <a href="{{ route('commodities.show', $maintenance->commodity) }}" class="font-semibold text-primary-600 hover:underline">
                                {{ $maintenance->commodity->name }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $maintenance->commodity->item_code }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Info Pencatatan</h3>
                </div>
                <div class="card-body text-sm text-gray-500">
                    <p><span class="text-gray-600 font-medium">Dicatat oleh:</span> {{ $maintenance->creator->name ?? '-' }}</p>
                    <p class="mt-1"><span class="text-gray-600 font-medium">Tanggal:</span> {{ $maintenance->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Verification Section - Full Width -->
    @if($maintenance->signature)
    <div class="mt-6">
        <div class="card shadow-none dark:shadow-none">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Verifikasi Digital</h3>
            </div>
            <div class="card-body">
                <div class="bg-info-50 dark:bg-gray-800 border border-info-200 dark:border-gray-700 rounded-lg p-6">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            <canvas id="qr-code" class="w-32 h-32 bg-white dark:bg-gray-700 rounded-lg dark:shadow-none"></canvas>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-info-900 dark:text-info-100 mb-3 text-lg">Tanda Tangan Digital</h4>
                            <p class="text-sm text-info-700 dark:text-info-200 mb-4">Scan QR code atau klik link untuk verifikasi keaslian laporan maintenance ini.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <span class="text-sm text-info-600 dark:text-info-300 font-medium">ID Verifikasi:</span>
                                    <div class="flex items-center gap-2 mt-2">
                                        <code class="text-sm font-mono bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-3 rounded border border-gray-200 dark:border-gray-600 flex-1 truncate">{{ substr($maintenance->signature->signature_hash, 0, 30) }}...</code>
                                        <button onclick="copyHash('{{ $maintenance->signature->signature_hash }}')" class="btn btn-secondary btn-sm flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="ml-1">Copy</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-info-600 dark:text-info-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">Valid dan terpercaya</span>
                                </div>
                            </div>
                            
                            <div class="pt-4 border-t border-info-200 dark:border-gray-600">
                                <p class="text-sm text-info-600 dark:text-info-300 mb-3 font-medium">Verifikasi laporan ini:</p>
                                <a href="{{ url('/verify/' . $maintenance->signature->signature_hash) }}" target="_blank" class="btn btn-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    <span class="ml-2">Buka Verifikasi</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($maintenance->signature)
    @push('scripts')
    <script src="/js/qrious.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('qr-code');
        if (canvas && typeof QRious !== 'undefined') {
            new QRious({
                element: canvas,
                value: '{{ url('/verify/' . $maintenance->signature->signature_hash) }}',
                size: 120,
                foreground: '#000000',
                background: '#ffffff'
            });
        }
    });

    function copyHash(hash) {
        navigator.clipboard.writeText(hash).then(function() {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 px-4 py-2 rounded-lg text-sm text-white bg-green-600 shadow-lg z-50';
            toast.innerHTML = '<i class="bx bx-check mr-1"></i> ID Verifikasi berhasil disalin!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }).catch(function(err) {
            alert('Gagal menyalin: ' + err);
        });
    }
    </script>
    @endpush
    @endif
</x-app-layout>
