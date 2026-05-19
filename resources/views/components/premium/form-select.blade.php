@props([
    'label' => null,
    'name' => '',
    'value' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
    'options' => [],
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
    
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->except('class')->merge([
            'class' => 'form-input' . 
                ($error || $errors->has($name) ? ' form-input-error' : '')
        ]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @if(old($name, $value) == $optionValue) selected @endif>
                {{ $optionLabel }}
            </option>
        @endforeach
        
        {{ $slot }}
    </select>
    
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
