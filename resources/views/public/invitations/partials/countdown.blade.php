{{-- Countdown Timer Component --}}
@props([
    'theme' => 'light', // light, dark, gold
    'size' => 'default' // small, default, large
])

@php
    $themes = [
        'light' => [
            'container' => 'bg-white/80 backdrop-blur-sm border border-ivory-200',
            'number' => 'text-charcoal-900',
            'label' => 'text-charcoal-500',
            'divider' => 'text-gold-500'
        ],
        'dark' => [
            'container' => 'bg-charcoal-900/80 backdrop-blur-sm border border-charcoal-700',
            'number' => 'text-white',
            'label' => 'text-charcoal-400',
            'divider' => 'text-gold-400'
        ],
        'gold' => [
            'container' => 'bg-gradient-to-br from-gold-500/20 to-gold-600/20 backdrop-blur-sm border border-gold-400/30',
            'number' => 'text-gold-400',
            'label' => 'text-gold-300/80',
            'divider' => 'text-gold-500'
        ]
    ];
    
    $sizes = [
        'small' => ['number' => 'text-2xl md:text-3xl', 'label' => 'text-[10px]', 'box' => 'w-14 h-16 md:w-16 md:h-20'],
        'default' => ['number' => 'text-3xl md:text-4xl', 'label' => 'text-xs', 'box' => 'w-16 h-20 md:w-20 md:h-24'],
        'large' => ['number' => 'text-4xl md:text-5xl', 'label' => 'text-sm', 'box' => 'w-20 h-24 md:w-24 md:h-28']
    ];
    
    $t = $themes[$theme] ?? $themes['light'];
    $s = $sizes[$size] ?? $sizes['default'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center justify-center gap-2 md:gap-4']) }}>
    {{-- Days --}}
    <div class="text-center">
        <div class="{{ $t['container'] }} {{ $s['box'] }} rounded-xl flex items-center justify-center shadow-lg">
            <span x-text="countdown.days.toString().padStart(2, '0')" class="font-display font-bold {{ $s['number'] }} {{ $t['number'] }}">00</span>
        </div>
        <p class="{{ $s['label'] }} {{ $t['label'] }} mt-2 font-medium uppercase tracking-wider">Days</p>
    </div>
    
    {{-- Divider --}}
    <span class="{{ $s['number'] }} {{ $t['divider'] }} font-display -mt-6">:</span>
    
    {{-- Hours --}}
    <div class="text-center">
        <div class="{{ $t['container'] }} {{ $s['box'] }} rounded-xl flex items-center justify-center shadow-lg">
            <span x-text="countdown.hours.toString().padStart(2, '0')" class="font-display font-bold {{ $s['number'] }} {{ $t['number'] }}">00</span>
        </div>
        <p class="{{ $s['label'] }} {{ $t['label'] }} mt-2 font-medium uppercase tracking-wider">Hours</p>
    </div>
    
    {{-- Divider --}}
    <span class="{{ $s['number'] }} {{ $t['divider'] }} font-display -mt-6">:</span>
    
    {{-- Minutes --}}
    <div class="text-center">
        <div class="{{ $t['container'] }} {{ $s['box'] }} rounded-xl flex items-center justify-center shadow-lg">
            <span x-text="countdown.minutes.toString().padStart(2, '0')" class="font-display font-bold {{ $s['number'] }} {{ $t['number'] }}">00</span>
        </div>
        <p class="{{ $s['label'] }} {{ $t['label'] }} mt-2 font-medium uppercase tracking-wider">Mins</p>
    </div>
    
    {{-- Divider --}}
    <span class="{{ $s['number'] }} {{ $t['divider'] }} font-display -mt-6">:</span>
    
    {{-- Seconds --}}
    <div class="text-center">
        <div class="{{ $t['container'] }} {{ $s['box'] }} rounded-xl flex items-center justify-center shadow-lg">
            <span x-text="countdown.seconds.toString().padStart(2, '0')" class="font-display font-bold {{ $s['number'] }} {{ $t['number'] }}">00</span>
        </div>
        <p class="{{ $s['label'] }} {{ $t['label'] }} mt-2 font-medium uppercase tracking-wider">Secs</p>
    </div>
</div>
