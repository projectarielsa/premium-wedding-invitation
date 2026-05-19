@props([
    'current' => 0,
    'max' => 1,
    'label' => 'Penggunaan',
    'showUpgrade' => true,
    'size' => 'md', // sm, md, lg
])

@php
    $percentage = $max > 0 ? min(100, round(($current / $max) * 100)) : 0;
    $isAtLimit = $current >= $max;
    $isNearLimit = $percentage >= 80;
    
    $barColor = match(true) {
        $isAtLimit => 'bg-red-500',
        $isNearLimit => 'bg-amber-500',
        default => 'bg-green-500',
    };
    
    $textColor = match(true) {
        $isAtLimit => 'text-red-600',
        $isNearLimit => 'text-amber-600',
        default => 'text-gray-600',
    };
    
    $sizeClasses = [
        'sm' => ['bar' => 'h-1.5', 'text' => 'text-xs'],
        'md' => ['bar' => 'h-2', 'text' => 'text-sm'],
        'lg' => ['bar' => 'h-3', 'text' => 'text-base'],
    ];
    $sizes = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    <div class="flex items-center justify-between">
        <span class="{{ $sizes['text'] }} text-gray-600">{{ $label }}</span>
        <span class="{{ $sizes['text'] }} font-medium {{ $textColor }}">
            {{ number_format($current) }} / {{ $max >= 99999 ? 'Unlimited' : number_format($max) }}
        </span>
    </div>
    
    <div class="w-full bg-gray-200 rounded-full {{ $sizes['bar'] }} overflow-hidden">
        <div class="{{ $barColor }} {{ $sizes['bar'] }} rounded-full transition-all duration-300" 
             style="width: {{ $percentage }}%"></div>
    </div>
    
    @if($isAtLimit && $showUpgrade)
        <div class="flex items-center gap-1 {{ $sizes['text'] }} text-red-600">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <span>Batas tercapai</span>
            <a href="{{ route('pricing') }}" class="font-medium hover:underline ml-1">Upgrade</a>
        </div>
    @elseif($isNearLimit && $showUpgrade)
        <div class="flex items-center gap-1 {{ $sizes['text'] }} text-amber-600">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span>Hampir penuh</span>
        </div>
    @endif
</div>
