<x-app-layout title="Detail Penghapusan">
    <div class="max-w-3xl">
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

        <!-- Commodity Info -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Barang yang Diajukan</h3>
            </div>
            <div class="card-body">
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <img src="{{ $disposal->commodity->primary_image_url }}" alt="" class="w-20 h-20 rounded-lg object-cover">
                    <div>
                        <a href="{{ route('commodities.show', $disposal->commodity) }}" class="font-semibold text-primary-600 hover:underline text-lg">
                            {{ $disposal->commodity->name }}
                        </a>
                        <p class="text-sm text-gray-500">{{ $disposal->commodity->item_code }}</p>
                        <p class="text-sm text-gray-500">Lokasi: {{ $disposal->commodity->location->name }}</p>
                        <span class="badge {{ $disposal->commodity->condition_badge_class }} mt-1">{{ $disposal->commodity->condition_label }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disposal Details -->
        <div class="card mb-6">
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
        <div class="card mb-6 border-danger-200">
            <div class="card-header bg-danger-50">
                <h3 class="font-semibold text-danger-700">Alasan Penolakan</h3>
            </div>
            <div class="card-body">
                <p>{{ $disposal->rejection_reason }}</p>
            </div>
        </div>
        @endif

        <!-- Actions -->
        @if($disposal->status === 'pending')
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Tindakan</h3>
            </div>
            <div class="card-body">
                @can('disposals.approve')
                <div class="flex gap-3">
                    <form action="{{ route('disposals.approve', $disposal) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn btn-success w-full" onclick="return confirm('Setujui penghapusan barang ini? Barang akan dihapus dari inventaris.')">
                            Setujui Penghapusan
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger flex-1" onclick="document.getElementById('rejectModal').classList.remove('hidden')">
                        Tolak
                    </button>
                </div>
                @endcan

                @if($disposal->requested_by === auth()->id())
                <form action="{{ route('disposals.destroy', $disposal) }}" method="POST" class="mt-3">
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
