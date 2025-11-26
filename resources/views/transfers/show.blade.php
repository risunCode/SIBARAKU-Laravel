<x-app-layout title="Detail Transfer">
    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('transfers.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
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

        <!-- Transfer Info -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Informasi Transfer</h3>
            </div>
            <div class="card-body">
                <!-- Commodity -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg mb-4">
                    <img src="{{ $transfer->commodity->primary_image_url }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                    <div>
                        <p class="font-medium">{{ $transfer->commodity->name }}</p>
                        <p class="text-sm text-gray-500">{{ $transfer->commodity->item_code }}</p>
                    </div>
                </div>

                <!-- From -> To -->
                <div class="flex items-center justify-center gap-4 py-6">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Dari</p>
                        <p class="font-semibold text-lg">{{ $transfer->fromLocation->name }}</p>
                    </div>
                    <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Ke</p>
                        <p class="font-semibold text-lg">{{ $transfer->toLocation->name }}</p>
                    </div>
                </div>

                <dl class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <dt class="text-sm text-gray-500">Pengaju</dt>
                        <dd class="font-medium">{{ $transfer->requester->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Tanggal Pengajuan</dt>
                        <dd class="font-medium">{{ $transfer->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($transfer->approver)
                    <div>
                        <dt class="text-sm text-gray-500">Diproses Oleh</dt>
                        <dd class="font-medium">{{ $transfer->approver->name }}</dd>
                    </div>
                    @endif
                    @if($transfer->transfer_date)
                    <div>
                        <dt class="text-sm text-gray-500">Tanggal Transfer</dt>
                        <dd class="font-medium">{{ $transfer->transfer_date->format('d M Y') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Reason -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Alasan Transfer</h3>
            </div>
            <div class="card-body">
                <p class="whitespace-pre-line">{{ $transfer->reason }}</p>
                @if($transfer->notes)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-500 mb-1">Catatan:</p>
                    <p class="text-sm">{{ $transfer->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($transfer->rejection_reason)
        <div class="card mb-6 border-danger-200">
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
            <div class="card-body">
                @can('transfers.approve')
                <div class="flex gap-3">
                    <form action="{{ route('transfers.approve', $transfer) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn btn-success w-full" onclick="return confirm('Setujui transfer ini?')">
                            Setujui
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger flex-1" onclick="document.getElementById('rejectModal').classList.remove('hidden')">
                        Tolak
                    </button>
                </div>
                @endcan

                @if($transfer->requested_by === auth()->id())
                <form action="{{ route('transfers.destroy', $transfer) }}" method="POST" class="mt-3">
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
            <div class="card-body">
                <form action="{{ route('transfers.complete', $transfer) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-full" onclick="return confirm('Selesaikan transfer dan pindahkan barang?')">
                        Selesaikan Transfer
                    </button>
                </form>
            </div>
        </div>
        @endcan
        @endif
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
