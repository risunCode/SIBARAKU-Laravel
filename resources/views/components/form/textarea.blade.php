@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => '',
    'rows' => 3,
    'helper' => null,
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

    <textarea 
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except('class')->merge(['class' => 'form-textarea']) }}
    >{{ old($name, $value) }}</textarea>

    @if($helper)
    <p class="text-xs text-gray-500 mt-1">{{ $helper }}</p>
    @endif

    @error($name)
    <p class="form-error">{{ $message }}</p>
    @enderror
</div>
