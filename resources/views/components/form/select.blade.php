@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Pilih...',
    'options' => [],
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

    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except('class')->merge(['class' => 'form-select']) }}
    >
        @if($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
            {{ $optionLabel }}
        </option>
        @endforeach

        {{ $slot }}
    </select>

    @if($helper)
    <p class="text-xs text-gray-500 mt-1">{{ $helper }}</p>
    @endif

    @error($name)
    <p class="form-error">{{ $message }}</p>
    @enderror
</div>
