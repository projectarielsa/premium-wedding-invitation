@props([
    'type' => 'info',
])

@php
    $types = [
        'success' => 'toast-success',
        'error' => 'toast-error',
        'warning' => 'toast-warning',
        'info' => 'toast-info',
    ];
    
    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];
    
    $iconColors = [
        'success' => 'text-emerald-500',
        'error' => 'text-red-500',
        'warning' => 'text-amber-500',
        'info' => 'text-blue-500',
    ];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-8"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-8"
    {{ $attributes->merge(['class' => 'toast ' . ($types[$type] ?? $types['info'])]) }}
>
    <svg class="w-5 h-5 {{ $iconColors[$type] ?? $iconColors['info'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icons[$type] ?? $icons['info'] !!}
    </svg>
    
    <p class="text-sm font-medium text-charcoal-700 flex-1">{{ $slot }}</p>
    
    <button x-on:click="show = false" class="text-charcoal-400 hover:text-charcoal-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
