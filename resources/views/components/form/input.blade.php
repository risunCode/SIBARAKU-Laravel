@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => '',
    'helper' => null,
    'autocomplete' => null,
])

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if($label)
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
        <span class="text-danger-500">*</span>
        @endif
    </label>
    @endif

    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $autocomplete ? "autocomplete=\"{$autocomplete}\"" : '' }}
        {{ $attributes->except('class')->merge(['class' => 'form-input']) }}
    >

    @if($helper)
    <p class="text-xs text-gray-500 mt-1">{{ $helper }}</p>
    @endif

    @error($name)
    <p class="form-error">{{ $message }}</p>
    @enderror
</div>
