@props([
    'align' => 'right',
    'width' => '56',
])

@php
    $alignmentClasses = [
        'left' => 'left-0',
        'right' => 'right-0',
    ];
    
    $widthClasses = [
        '48' => 'w-48',
        '56' => 'w-56',
        '64' => 'w-64',
    ];
@endphp

<div x-data="{ open: false }" class="relative inline-block text-left">
    <div>
        <button 
            x-on:click="open = !open" 
            type="button"
            class="p-2 rounded-lg text-charcoal-500 hover:text-charcoal-700 hover:bg-ivory-100 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-gold-500"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
        </button>
    </div>
    
    <div
        x-show="open"
        x-on:click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="dropdown-menu {{ $alignmentClasses[$align] ?? $alignmentClasses['right'] }} {{ $widthClasses[$width] ?? $widthClasses['56'] }}"
        x-cloak
    >
        {{ $slot }}
    </div>
</div>
