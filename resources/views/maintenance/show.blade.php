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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
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
</x-app-layout>
