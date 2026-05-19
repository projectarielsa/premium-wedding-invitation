@props([
    'label' => null,
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'border-ivory-200',
        'gold' => 'border-gold-200',
        'dark' => 'border-charcoal-200',
    ];
@endphp

@if($label)
    <div {{ $attributes->merge(['class' => 'relative my-6']) }}>
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t {{ $variants[$variant] ?? $variants['default'] }}"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-ivory-100 text-charcoal-500">{{ $label }}</span>
        </div>
    </div>
@else
    <hr {{ $attributes->merge(['class' => 'divider ' . ($variants[$variant] ?? $variants['default'])]) }} />
@endif
