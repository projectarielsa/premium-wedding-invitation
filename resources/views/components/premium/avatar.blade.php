@props([
    'src' => null,
    'alt' => '',
    'name' => '',
    'size' => 'md',
])

@php
    $sizes = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg',
        '2xl' => 'w-20 h-20 text-xl',
    ];
    
    $initials = collect(explode(' ', $name))
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->join('');
@endphp

@if($src)
    <img 
        src="{{ $src }}" 
        alt="{{ $alt ?: $name }}" 
        {{ $attributes->merge(['class' => 'avatar ' . ($sizes[$size] ?? $sizes['md'])]) }}
    />
@else
    <div {{ $attributes->merge(['class' => 'avatar flex items-center justify-center bg-gold-100 text-gold-700 font-semibold ' . ($sizes[$size] ?? $sizes['md'])]) }}>
        {{ $initials ?: '?' }}
    </div>
@endif
