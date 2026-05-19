@props([
    'variant' => 'default',
])

@php
    $variants = [
        'default' => [
            'text' => 'text-charcoal-800',
            'accent' => 'text-gold-500',
        ],
        'light' => [
            'text' => 'text-white',
            'accent' => 'text-gold-300',
        ],
        'gold' => [
            'text' => 'text-gold-600',
            'accent' => 'text-gold-400',
        ],
    ];
    $style = $variants[$variant] ?? $variants['default'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    {{-- Logo Icon --}}
    <div class="relative">
        <div class="w-10 h-10 rounded-xl bg-gradient-gold flex items-center justify-center shadow-gold">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <div class="absolute -top-1 -right-1 w-3 h-3 bg-gold-400 rounded-full border-2 border-white"></div>
    </div>
    
    {{-- Logo Text --}}
    <div class="flex flex-col">
        <span class="font-display font-bold text-lg leading-tight {{ $style['text'] }}">
            Wedding<span class="{{ $style['accent'] }}">Invite</span>
        </span>
        <span class="text-xs font-medium tracking-wider uppercase {{ $style['accent'] }} opacity-80">Premium</span>
    </div>
</div>
