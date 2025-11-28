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
        <span class="text-danger-500" aria-label="wajib diisi">*</span>
        @endif
    </label>
    @endif

    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required aria-required="true"' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $autocomplete ? "autocomplete=\"{$autocomplete}\"" : '' }}
        aria-describedby="{{ $helper ? $name . '_helper' : '' }} {{ $errors->has($name) ? $name . '_error' : '' }}"
        {{ $attributes->except('class')->merge(['class' => 'form-input']) }}
    >

    @if($helper)
    <p id="{{ $name }}_helper" class="text-xs text-gray-500 mt-1">{{ $helper }}</p>
    @endif

    @error($name)
    <p id="{{ $name }}_error" class="form-error" role="alert">{{ $message }}</p>
    @enderror
</div>
