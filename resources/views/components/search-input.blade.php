@props([
    'name' => 'search',
    'value' => null,
    'placeholder' => 'Cari...',
    'action' => null
])

<div class="relative">
    <input 
        type="text" 
        name="{{ $name }}" 
        id="{{ $name }}"
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-input pr-10']) }}
    >
    
    @if($value)
    <button 
        type="button" 
        onclick="clearSearch('{{ $name }}', '{{ $action ?? 'this.form' }}')"
        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
        title="Hapus pencarian"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    @else
    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    @endif
</div>

<script>
function clearSearch(inputName, formSelector) {
    const form = formSelector === 'this.form' ? document.querySelector(`input[name="${inputName}"]`).form : document.querySelector(formSelector);
    const input = form.querySelector(`input[name="${inputName}"]`);
    input.value = '';
    form.submit();
}
</script>
