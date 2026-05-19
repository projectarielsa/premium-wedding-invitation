@props([
    'label' => null,
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'hint' => null,
    'rows' => 4,
])

<div {{ $attributes->only('class')->merge(['class' => 'w-full']) }}>
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        {{ $attributes->except('class')->merge([
            'class' => 'form-input resize-none' . 
                ($error || $errors->has($name) ? ' form-input-error' : '')
        ]) }}
    >{{ old($name, $value) }}</textarea>
    
    @if($hint && !$error && !$errors->has($name))
        <p class="form-helper">{{ $hint }}</p>
    @endif
    
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
    
    @if($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>
