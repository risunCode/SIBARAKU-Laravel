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
        <span class="text-danger-500" aria-label="wajib diisi">*</span>
        @endif
    </label>
    @endif

    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required aria-required="true"' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        aria-describedby="{{ $helper ? $name . '_helper' : '' }} {{ $errors->has($name) ? $name . '_error' : '' }}"
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
    <p id="{{ $name }}_helper" class="text-xs text-gray-500 mt-1">{{ $helper }}</p>
    @endif

    @error($name)
    <p id="{{ $name }}_error" class="form-error" role="alert">{{ $message }}</p>
    @enderror
</div>
