<x-app-layout title="Detail Kategori">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Detail Kategori: {{ $category->name }}</h2>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Kode</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Parent</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->parent?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jumlah Barang</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->commodities_count }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->description ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </dd>
                    </div>
                </dl>

                @if($category->children->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Sub-kategori</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child) }}" class="badge badge-primary">
                            {{ $child->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer flex gap-2">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
