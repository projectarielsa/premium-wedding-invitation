@props([
    'type' => 'info',
    'dismissible' => false,
    'title' => null,
])

@php
    $types = [
        'info' => [
            'bg' => 'bg-blue-50 border-blue-200',
            'icon' => 'text-blue-500',
            'title' => 'text-blue-800',
            'text' => 'text-blue-700',
        ],
        'success' => [
            'bg' => 'bg-emerald-50 border-emerald-200',
            'icon' => 'text-emerald-500',
            'title' => 'text-emerald-800',
            'text' => 'text-emerald-700',
        ],
        'warning' => [
            'bg' => 'bg-amber-50 border-amber-200',
            'icon' => 'text-amber-500',
            'title' => 'text-amber-800',
            'text' => 'text-amber-700',
        ],
        'danger' => [
            'bg' => 'bg-red-50 border-red-200',
            'icon' => 'text-red-500',
            'title' => 'text-red-800',
            'text' => 'text-red-700',
        ],
    ];
    
    $style = $types[$type] ?? $types['info'];
    
    $icons = [
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
        'danger' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];
@endphp

<div 
    @if($dismissible) x-data="{ show: true }" x-show="show" @endif
    {{ $attributes->merge(['class' => 'flex gap-3 p-4 rounded-xl border ' . $style['bg']]) }}
>
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $style['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icons[$type] ?? $icons['info'] !!}
    </svg>
    
    <div class="flex-1">
        @if($title)
            <h4 class="font-semibold {{ $style['title'] }} mb-1">{{ $title }}</h4>
        @endif
        <p class="text-sm {{ $style['text'] }}">{{ $slot }}</p>
    </div>
    
    @if($dismissible)
        <button x-on:click="show = false" class="flex-shrink-0 {{ $style['icon'] }} hover:opacity-75 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif
</div>
