<x-app-layout title="Edit Maintenance">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('maintenance.show', $maintenance) }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Edit Maintenance</h2>
        </div>

        <div class="card">
            <form action="{{ route('maintenance.update', $maintenance) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body space-y-4">
                    <!-- Commodity Info -->
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <img src="{{ $maintenance->commodity->primary_image_url }}" alt="" class="w-12 h-12 rounded-lg object-cover">
                        <div>
                            <p class="font-medium">{{ $maintenance->commodity->name }}</p>
                            <p class="text-sm text-gray-500">{{ $maintenance->commodity->item_code }}</p>
                        </div>
                    </div>

                    <x-form.input label="Jenis Maintenance" name="maintenance_type" :value="$maintenance->maintenance_type" required />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Tanggal Maintenance" name="maintenance_date" type="date" :value="$maintenance->maintenance_date->format('Y-m-d')" required />
                        <x-form.input label="Biaya (Rp)" name="cost" type="number" min="0" :value="$maintenance->cost" />
                    </div>

                    <x-form.select label="Kondisi Setelah Maintenance" name="condition_after" :value="$maintenance->condition_after" required :options="[
                        'baik' => 'Baik',
                        'rusak_ringan' => 'Rusak Ringan',
                        'rusak_berat' => 'Rusak Berat'
                    ]" />

                    <x-form.input label="Dilakukan Oleh" name="performed_by" :value="$maintenance->performed_by" />

                    <x-form.textarea label="Deskripsi Pekerjaan" name="description" :value="$maintenance->description" rows="3" />

                    <x-form.input label="Jadwal Maintenance Berikutnya" name="next_maintenance_date" type="date" :value="$maintenance->next_maintenance_date?->format('Y-m-d')" />
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('maintenance.show', $maintenance) }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
