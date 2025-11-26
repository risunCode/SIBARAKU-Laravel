@props(['paginator'])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Results Info -->
    <div class="text-sm" style="color: var(--text-secondary);">
        Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
    </div>

    <!-- Per Page Options -->
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="text-sm" style="color: var(--text-secondary);">Tampilkan:</span>
            <select onchange="changePerPage(this.value)" class="px-3 py-1 text-sm rounded-lg border" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                @if($paginator->total() <= 500)
                <option value="{{ $paginator->total() }}" {{ request('per_page') == $paginator->total() ? 'selected' : '' }}>Semua</option>
                @endif
            </select>
        </div>

        <!-- Navigation Buttons -->
        @if($paginator->hasPages())
        <div class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if($paginator->onFirstPage())
                <button disabled class="px-3 py-1 text-sm rounded-lg border opacity-50 cursor-not-allowed" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-secondary);">
                    ‹ Prev
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-sm rounded-lg border hover:opacity-80 transition-opacity" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    ‹ Prev
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach($elements ?? [] as $element)
                {{-- "Three Dots" Separator --}}
                @if(is_string($element))
                    <span class="px-3 py-1 text-sm" style="color: var(--text-secondary);">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if(is_array($element))
                    @foreach($element as $page => $url)
                        @if($page == $paginator->currentPage())
                            <button class="px-3 py-1 text-sm rounded-lg border font-medium" style="background-color: var(--accent-color); border-color: var(--accent-color); color: white;">
                                {{ $page }}
                            </button>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 text-sm rounded-lg border hover:opacity-80 transition-opacity" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-sm rounded-lg border hover:opacity-80 transition-opacity" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    Next ›
                </a>
            @else
                <button disabled class="px-3 py-1 text-sm rounded-lg border opacity-50 cursor-not-allowed" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-secondary);">
                    Next ›
                </button>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page when changing per_page
    window.location.href = url.toString();
}
</script>
