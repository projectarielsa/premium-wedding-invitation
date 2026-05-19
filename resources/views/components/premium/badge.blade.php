@props([
    'variant' => 'neutral',
    'size' => 'md',
    'dot' => false,
])

@php
    $variants = [
        'gold' => 'badge-gold',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
        'info' => 'badge-info',
        'neutral' => 'badge-neutral',
        'published' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'draft' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'expired' => 'bg-charcoal-100 text-charcoal-600 border border-charcoal-200',
        'attending' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'not_attending' => 'bg-red-50 text-red-700 border border-red-200',
        'maybe' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'pending' => 'bg-blue-50 text-blue-700 border border-blue-200',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];
    
    $dotColors = [
        'gold' => 'bg-gold-500',
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-blue-500',
        'neutral' => 'bg-charcoal-400',
        'published' => 'bg-emerald-500',
        'draft' => 'bg-amber-500',
        'expired' => 'bg-charcoal-400',
        'attending' => 'bg-emerald-500',
        'not_attending' => 'bg-red-500',
        'maybe' => 'bg-amber-500',
        'pending' => 'bg-blue-500',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . ($variants[$variant] ?? $variants['neutral']) . ' ' . ($sizes[$size] ?? $sizes['md'])]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$variant] ?? $dotColors['neutral'] }}"></span>
    @endif
    {{ $slot }}
</span>
