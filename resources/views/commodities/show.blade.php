<x-app-layout title="Detail Barang">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('commodities.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">{{ $commodity->name }}</h2>
            <p class="text-sm text-gray-500">Kode: {{ $commodity->item_code }}</p>
        </div>
        <div class="flex gap-2">
            @can('commodities.edit')
            <a href="{{ route('commodities.edit', $commodity) }}" class="btn btn-outline">Edit</a>
            @endcan
            @can('transfers.create')
            <a href="{{ route('transfers.create', ['commodity_id' => $commodity->id]) }}" class="btn btn-primary">Transfer</a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Images -->
            <div class="card">
                <div class="card-body">
                    @if($commodity->images->count() > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($commodity->images as $image)
                        <img src="{{ $image->url }}" alt="{{ $commodity->name }}" class="w-full h-24 object-cover rounded-lg {{ $image->is_primary ? 'ring-2 ring-primary-500' : '' }}">
                        @endforeach
                    </div>
                    @else
                    <div class="flex items-center justify-center h-48 bg-gray-100 rounded-lg">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detail Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Informasi Barang</h3>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Kategori</dt>
                            <dd class="font-medium">{{ $commodity->category->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Lokasi</dt>
                            <dd class="font-medium">{{ $commodity->location->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Merk/Brand</dt>
                            <dd class="font-medium">{{ $commodity->brand ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Model/Tipe</dt>
                            <dd class="font-medium">{{ $commodity->model ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Serial Number</dt>
                            <dd class="font-medium font-mono">{{ $commodity->serial_number ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Kondisi</dt>
                            <dd><span class="badge {{ $commodity->condition_badge_class }}">{{ $commodity->condition_label }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Jumlah</dt>
                            <dd class="font-medium">{{ $commodity->quantity }} unit</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Penanggung Jawab</dt>
                            <dd class="font-medium">{{ $commodity->responsible_person ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Acquisition Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Informasi Perolehan</h3>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Cara Perolehan</dt>
                            <dd class="font-medium">{{ $commodity->acquisition_type_label }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Sumber Perolehan</dt>
                            <dd class="font-medium">{{ $commodity->acquisition_source ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Tahun Perolehan</dt>
                            <dd class="font-medium">{{ $commodity->purchase_year ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Harga Perolehan</dt>
                            <dd class="font-medium">{{ $commodity->formatted_price }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($commodity->specifications || $commodity->notes)
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Keterangan Tambahan</h3>
                </div>
                <div class="card-body space-y-4">
                    @if($commodity->specifications)
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">Spesifikasi</dt>
                        <dd class="text-sm whitespace-pre-line">{{ $commodity->specifications }}</dd>
                    </div>
                    @endif
                    @if($commodity->notes)
                    <div>
                        <dt class="text-sm text-gray-500 mb-1">Catatan</dt>
                        <dd class="text-sm whitespace-pre-line">{{ $commodity->notes }}</dd>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="card-body space-y-2">
                    @can('maintenance.create')
                    <a href="{{ route('maintenance.create', ['commodity_id' => $commodity->id]) }}" class="btn btn-outline w-full justify-start">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                        Catat Maintenance
                    </a>
                    @endcan
                    @can('disposals.create')
                    <a href="{{ route('disposals.create', ['commodity_id' => $commodity->id]) }}" class="btn btn-outline w-full justify-start text-danger-600 hover:bg-danger-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Ajukan Penghapusan
                    </a>
                    @endcan
                    <a href="{{ route('reports.kib', ['commodity_id' => $commodity->id]) }}" class="btn btn-outline w-full justify-start" target="_blank">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak KIB
                    </a>
                </div>
            </div>

            <!-- Meta Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Informasi Sistem</h3>
                </div>
                <div class="card-body text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat oleh</span>
                        <span>{{ $commodity->creator->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat pada</span>
                        <span>{{ $commodity->created_at->format('d M Y H:i') }}</span>
                    </div>
                    @if($commodity->updater)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diubah oleh</span>
                        <span>{{ $commodity->updater->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diubah pada</span>
                        <span>{{ $commodity->updated_at->format('d M Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transfer History -->
            @if($commodity->transfers->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Riwayat Transfer</h3>
                </div>
                <div class="card-body space-y-3 max-h-48 overflow-y-auto">
                    @foreach($commodity->transfers->take(5) as $transfer)
                    <div class="text-sm border-l-2 border-primary-200 pl-3">
                        <p class="font-medium">{{ $transfer->fromLocation->name }} → {{ $transfer->toLocation->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $transfer->created_at->format('d M Y') }} - <span class="badge {{ $transfer->status_badge_class }}">{{ $transfer->status_label }}</span></p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
