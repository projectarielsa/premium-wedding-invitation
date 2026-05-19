@props([
    'package' => null,
    'size' => 'md',
    'showExpiry' => false,
    'expiresAt' => null,
])

@php
    $packageName = $package?->name ?? 'Basic';
    $packageSlug = $package?->slug ?? 'basic';
    
    $colorMap = [
        'basic' => 'bg-gray-100 text-gray-700 border-gray-200',
        'starter' => 'bg-blue-100 text-blue-700 border-blue-200',
        'premium' => 'bg-amber-100 text-amber-700 border-amber-200',
        'professional' => 'bg-purple-100 text-purple-700 border-purple-200',
        'enterprise' => 'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border-amber-300',
    ];
    
    $sizeMap = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        'lg' => 'px-3 py-1.5 text-base',
    ];
    
    $colorClass = $colorMap[$packageSlug] ?? $colorMap['basic'];
    $sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full border font-medium {$colorClass} {$sizeClass}"]) }}>
    @if($packageSlug !== 'basic')
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
        </svg>
    @endif
    {{ $packageName }}
    @if($showExpiry && $expiresAt)
        <span class="text-xs opacity-75">
            ({{ $expiresAt->diffForHumans() }})
        </span>
    @endif
</span>
