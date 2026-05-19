@props([
    'type' => 'text',
    'lines' => 1,
    'height' => null,
    'width' => null,
])

@php
    $baseClasses = 'skeleton';
    
    $typeClasses = [
        'text' => 'skeleton-text h-4',
        'title' => 'skeleton-text h-6',
        'avatar' => 'skeleton-circle w-10 h-10',
        'avatar-sm' => 'skeleton-circle w-8 h-8',
        'avatar-lg' => 'skeleton-circle w-14 h-14',
        'image' => 'skeleton w-full h-48',
        'card' => 'skeleton w-full h-32',
        'button' => 'skeleton h-10 w-24',
    ];
    
    $styles = [];
    if ($height) $styles[] = "height: {$height}";
    if ($width) $styles[] = "width: {$width}";
    $styleString = implode('; ', $styles);
@endphp

@if($type === 'text' && $lines > 1)
    <div {{ $attributes->merge(['class' => 'space-y-2']) }}>
        @for($i = 0; $i < $lines; $i++)
            <div class="{{ $typeClasses['text'] }}" style="{{ $i === $lines - 1 ? 'width: 70%' : 'width: 100%' }}"></div>
        @endfor
    </div>
@else
    <div 
        {{ $attributes->merge(['class' => $typeClasses[$type] ?? $baseClasses]) }}
        @if($styleString) style="{{ $styleString }}" @endif
    ></div>
@endif
