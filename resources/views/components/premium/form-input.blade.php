@props([
    'type' => 'text',
    'label' => null,
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'hint' => null,
    'prefix' => null,
    'suffix' => null,
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
    
    <div class="relative">
        @if($prefix)
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="text-charcoal-400 text-sm">{{ $prefix }}</span>
            </div>
        @endif
        
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            {{ $attributes->except('class')->merge([
                'class' => 'form-input' . 
                    ($error || $errors->has($name) ? ' form-input-error' : '') .
                    ($prefix ? ' pl-10' : '') .
                    ($suffix ? ' pr-10' : '')
            ]) }}
        />
        
        @if($suffix)
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                <span class="text-charcoal-400 text-sm">{{ $suffix }}</span>
            </div>
        @endif
    </div>
    
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
