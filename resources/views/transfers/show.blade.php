<x-app-layout title="Detail Transfer">
    <div class="mb-6">
        <a href="{{ route('transfers.index') }}" class="text-sm text-gray-600 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <div class="flex items-center justify-between mt-2">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $transfer->transfer_number }}</h2>
                <span class="badge {{ $transfer->status_badge_class }}">{{ $transfer->status_label }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transfer Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Informasi Transfer</h3>
                </div>
                <div class="card-body">
                    <!-- Commodity -->
                    @if($transfer->commodity)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg mb-4">
                        <img src="{{ $transfer->commodity->primary_image_url ?? asset('images/no-image.png') }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                        <div>
                            <a href="{{ route('commodities.show', $transfer->commodity) }}" class="font-medium text-primary-600 hover:underline">{{ $transfer->commodity->name }}</a>
                            <p class="text-sm text-gray-600">{{ $transfer->commodity->item_code }}</p>
                        </div>
                    </div>
                    @else
                    <div class="p-4 bg-red-50 rounded-lg mb-4 border border-red-200">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="font-medium text-red-800">Barang Tidak Tersedia</p>
                                <p class="text-sm text-red-600">Barang yang di-transfer sudah tidak ada atau telah dihapus dari sistem</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- From -> To -->
                    <div class="flex items-center justify-center gap-4 py-6 bg-gray-50 rounded-lg">
                        <div class="text-center flex-1">
                            <p class="text-sm text-gray-600">Dari</p>
                            <p class="font-semibold text-lg">{{ $transfer->fromLocation->name }}</p>
                        </div>
                        <svg class="w-8 h-8 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <div class="text-center flex-1">
                            <p class="text-sm text-gray-600">Ke</p>
                            <p class="font-semibold text-lg">{{ $transfer->toLocation->name }}</p>
                        </div>
                    </div>

                    <dl class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <dt class="text-sm text-gray-600">Pengaju</dt>
                            <dd class="font-medium">{{ $transfer->requester->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Tanggal Pengajuan</dt>
                            <dd class="font-medium">{{ $transfer->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @if($transfer->approver)
                        <div>
                            <dt class="text-sm text-gray-600">Diproses Oleh</dt>
                            <dd class="font-medium">{{ $transfer->approver->name }}</dd>
                        </div>
                        @endif
                        @if($transfer->transfer_date)
                        <div>
                            <dt class="text-sm text-gray-600">Tanggal Transfer</dt>
                            <dd class="font-medium">{{ $transfer->transfer_date->format('d M Y') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

                    </div>

        <!-- Right Column (1/3) -->
        <div class="space-y-6">
            <!-- Reason -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Alasan Transfer</h3>
                </div>
                <div class="card-body">
                    <p class="whitespace-pre-line">{{ $transfer->reason }}</p>
                    @if($transfer->notes)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600 mb-1">Catatan:</p>
                        <p class="text-sm">{{ $transfer->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($transfer->rejection_reason)
            <div class="card border-danger-200">
                <div class="card-header bg-danger-50">
                    <h3 class="font-semibold text-danger-700">Alasan Penolakan</h3>
                </div>
                <div class="card-body">
                    <p>{{ $transfer->rejection_reason }}</p>
                </div>
            </div>
            @endif

            <!-- Actions -->
            @if($transfer->status === 'pending')
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Tindakan</h3>
                </div>
                <div class="card-body space-y-3">
                    @can('transfers.approve')
                    <form action="{{ route('transfers.approve', $transfer) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-full" onclick="return confirm('Setujui transfer ini?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Setujui
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger w-full" onclick="document.getElementById('rejectModal').classList.remove('hidden')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak
                    </button>
                    @endcan

                    @if($transfer->requested_by === auth()->id())
                    <form action="{{ route('transfers.destroy', $transfer) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline w-full" onclick="return confirm('Batalkan pengajuan transfer ini?')">
                            Batalkan Pengajuan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

            @if($transfer->status === 'approved')
            @can('transfers.approve')
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Selesaikan Transfer</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transfers.complete', $transfer) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full" onclick="return confirm('Selesaikan transfer dan pindahkan barang?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Selesaikan Transfer
                        </button>
                    </form>
                </div>
            </div>
            @endcan
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
                                <p class="text-xs text-gray-600">{{ $transfer->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @if($transfer->status !== 'pending')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full {{ $transfer->status === 'rejected' ? 'bg-danger-500' : 'bg-success-500' }}"></div>
                            <div>
                                <p class="text-sm font-medium">{{ $transfer->status === 'rejected' ? 'Ditolak' : 'Disetujui' }}</p>
                                <p class="text-xs text-gray-600">{{ $transfer->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($transfer->status === 'completed')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full bg-primary-500"></div>
                            <div>
                                <p class="text-sm font-medium">Transfer Selesai</p>
                                <p class="text-xs text-gray-600">{{ $transfer->transfer_date?->format('d M Y') }}</p>
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
                <h3 class="text-lg font-semibold mb-4">Tolak Transfer</h3>
                <form action="{{ route('transfers.reject', $transfer) }}" method="POST">
                    @csrf
                    <x-form.textarea label="Alasan Penolakan" name="rejection_reason" required rows="3" placeholder="Jelaskan alasan penolakan..." />
                    <div class="flex gap-3 mt-4">
                        <button type="button" class="btn btn-outline flex-1" onclick="document.getElementById('rejectModal').classList.add('hidden')">Batal</button>
                        <button type="submit" class="btn btn-danger flex-1">Tolak Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
