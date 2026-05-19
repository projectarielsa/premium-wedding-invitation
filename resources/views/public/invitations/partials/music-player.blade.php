{{-- Music Player Component (Decorative Indicator) --}}
@props([
    'theme' => 'light' // light, dark, gold
])

@php
    $themes = [
        'light' => [
            'bg' => 'bg-white/90 border-ivory-200',
            'text' => 'text-charcoal-700',
            'muted' => 'text-charcoal-500',
            'bar' => 'bg-gold-500'
        ],
        'dark' => [
            'bg' => 'bg-charcoal-800/90 border-charcoal-700',
            'text' => 'text-white',
            'muted' => 'text-charcoal-400',
            'bar' => 'bg-gold-400'
        ],
        'gold' => [
            'bg' => 'bg-charcoal-900/90 border-gold-500/30',
            'text' => 'text-gold-400',
            'muted' => 'text-gold-300/70',
            'bar' => 'bg-gold-500'
        ]
    ];
    
    $t = $themes[$theme] ?? $themes['light'];
@endphp

<div 
    x-show="musicUrl"
    {{ $attributes->merge(['class' => $t['bg'] . ' backdrop-blur-md rounded-2xl border shadow-lg p-4']) }}
>
    <div class="flex items-center gap-4">
        {{-- Album Art / Icon --}}
        <div class="w-12 h-12 rounded-xl bg-gold-500/10 flex items-center justify-center flex-shrink-0">
            <svg 
                class="w-6 h-6 text-gold-500"
                :class="{ 'animate-pulse': isPlaying }"
                fill="currentColor" 
                viewBox="0 0 24 24"
            >
                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
            </svg>
        </div>

        {{-- Song Info --}}
        <div class="flex-1 min-w-0">
            <p class="font-medium {{ $t['text'] }} truncate">Wedding Soundtrack</p>
            <p class="text-sm {{ $t['muted'] }} truncate">Background Music</p>
        </div>

        {{-- Sound Bars Animation --}}
        <div class="flex items-end gap-1 h-8" x-show="isPlaying">
            <div class="{{ $t['bar'] }} w-1 rounded-full animate-[soundbar_0.5s_ease-in-out_infinite]" style="height: 60%; animation-delay: 0s;"></div>
            <div class="{{ $t['bar'] }} w-1 rounded-full animate-[soundbar_0.5s_ease-in-out_infinite]" style="height: 100%; animation-delay: 0.1s;"></div>
            <div class="{{ $t['bar'] }} w-1 rounded-full animate-[soundbar_0.5s_ease-in-out_infinite]" style="height: 40%; animation-delay: 0.2s;"></div>
            <div class="{{ $t['bar'] }} w-1 rounded-full animate-[soundbar_0.5s_ease-in-out_infinite]" style="height: 80%; animation-delay: 0.3s;"></div>
        </div>

        {{-- Paused State --}}
        <div x-show="!isPlaying" class="flex items-center gap-1 h-8 opacity-50">
            <div class="{{ $t['bar'] }} w-1 h-2 rounded-full"></div>
            <div class="{{ $t['bar'] }} w-1 h-3 rounded-full"></div>
            <div class="{{ $t['bar'] }} w-1 h-2 rounded-full"></div>
            <div class="{{ $t['bar'] }} w-1 h-4 rounded-full"></div>
        </div>

        {{-- Play/Pause Button --}}
        <button 
            x-on:click="toggleMusic()"
            class="w-10 h-10 rounded-full bg-gold-500 text-white flex items-center justify-center transition-transform hover:scale-110 active:scale-95 shadow-gold"
        >
            <svg x-show="!isPlaying" class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
            </svg>
            <svg x-show="isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
            </svg>
        </button>
    </div>
</div>

<style>
@keyframes soundbar {
    0%, 100% { transform: scaleY(0.3); }
    50% { transform: scaleY(1); }
}
</style>
