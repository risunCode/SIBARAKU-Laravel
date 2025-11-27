<x-app-layout title="Detail Penghapusan">
    <div class="mb-6">
        <a href="{{ route('disposals.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <div class="flex items-center justify-between mt-2">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $disposal->disposal_number }}</h2>
                <span class="badge {{ $disposal->status_badge_class }}">{{ $disposal->status_label }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Disposal Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Detail Pengajuan</h3>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Alasan Penghapusan</dt>
                            <dd class="font-medium">{{ $disposal->reason_label }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Taksiran Nilai Sisa</dt>
                            <dd class="font-medium">{{ $disposal->formatted_value }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Pengaju</dt>
                            <dd class="font-medium">{{ $disposal->requester->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Tanggal Pengajuan</dt>
                            <dd class="font-medium">{{ $disposal->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @if($disposal->approver)
                        <div>
                            <dt class="text-sm text-gray-500">Diproses Oleh</dt>
                            <dd class="font-medium">{{ $disposal->approver->name }}</dd>
                        </div>
                        @endif
                        @if($disposal->disposal_date)
                        <div>
                            <dt class="text-sm text-gray-500">Tanggal Penghapusan</dt>
                            <dd class="font-medium">{{ $disposal->disposal_date->format('d M Y') }}</dd>
                        </div>
                        @endif
                    </dl>

                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-2">Justifikasi:</p>
                        <p class="whitespace-pre-line">{{ $disposal->description }}</p>
                    </div>

                    @if($disposal->notes)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-2">Catatan:</p>
                        <p class="text-sm">{{ $disposal->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($disposal->rejection_reason)
            <div class="card border-danger-200">
                <div class="card-header bg-danger-50">
                    <h3 class="font-semibold text-danger-700">Alasan Penolakan</h3>
                </div>
                <div class="card-body">
                    <p>{{ $disposal->rejection_reason }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column (1/3) -->
        <div class="space-y-6">
            <!-- Commodity Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Barang yang Diajukan</h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center gap-4">
                        <img src="{{ $disposal->commodity->primary_image_url }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                        <div>
                            <a href="{{ route('commodities.show', $disposal->commodity) }}" class="font-semibold text-primary-600 hover:underline">
                                {{ $disposal->commodity->name }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $disposal->commodity->item_code }}</p>
                            <span class="badge {{ $disposal->commodity->condition_badge_class }} mt-1">{{ $disposal->commodity->condition_label }}</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t text-sm text-gray-500">
                        <p>Lokasi: {{ $disposal->commodity->location->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($disposal->status === 'pending')
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Tindakan</h3>
                </div>
                <div class="card-body space-y-3">
                    @can('disposals.approve')
                    <form action="{{ route('disposals.approve', $disposal) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-full" onclick="return confirm('Setujui penghapusan barang ini? Barang akan dihapus dari inventaris.')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Setujui
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger w-full" onclick="document.getElementById('rejectModal').classList.remove('hidden')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak
                    </button>
                    @endcan

                    @if($disposal->requested_by === auth()->id())
                    <form action="{{ route('disposals.destroy', $disposal) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline w-full" onclick="return confirm('Batalkan pengajuan ini?')">
                            Batalkan Pengajuan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Timeline</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full bg-primary-500"></div>
                            <div>
                                <p class="text-sm font-medium">Pengajuan Dibuat</p>
                                <p class="text-xs text-gray-500">{{ $disposal->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @if($disposal->status !== 'pending')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full {{ $disposal->status === 'rejected' ? 'bg-danger-500' : 'bg-success-500' }}"></div>
                            <div>
                                <p class="text-sm font-medium">{{ $disposal->status === 'rejected' ? 'Ditolak' : 'Disetujui' }}</p>
                                <p class="text-xs text-gray-500">{{ $disposal->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Tolak Penghapusan</h3>
                <form action="{{ route('disposals.reject', $disposal) }}" method="POST">
                    @csrf
                    <x-form.textarea label="Alasan Penolakan" name="rejection_reason" required rows="3" placeholder="Jelaskan alasan penolakan..." />
                    <div class="flex gap-3 mt-4">
                        <button type="button" class="btn btn-outline flex-1" onclick="document.getElementById('rejectModal').classList.add('hidden')">Batal</button>
                        <button type="submit" class="btn btn-danger flex-1">Tolak Penghapusan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
