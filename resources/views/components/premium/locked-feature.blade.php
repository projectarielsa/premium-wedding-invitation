@props([
    'feature' => 'feature',
    'title' => 'Fitur Terkunci',
    'message' => null,
    'icon' => 'lock',
    'color' => 'amber',
    'compact' => false,
])

@php
    $colorClasses = [
        'amber' => ['bg' => 'bg-amber-100', 'icon' => 'text-amber-600', 'btn' => 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500'],
        'purple' => ['bg' => 'bg-purple-100', 'icon' => 'text-purple-600', 'btn' => 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500'],
        'blue' => ['bg' => 'bg-blue-100', 'icon' => 'text-blue-600', 'btn' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'],
        'green' => ['bg' => 'bg-green-100', 'icon' => 'text-green-600', 'btn' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500'],
    ];
    $colors = $colorClasses[$color] ?? $colorClasses['amber'];
    
    $defaultMessages = [
        'analytics' => 'Upgrade paket Anda untuk mengakses fitur analitik lengkap.',
        'export' => 'Upgrade paket Anda untuk mengakses fitur export data.',
        'qr_checkin' => 'Upgrade paket Anda untuk mengakses fitur QR Check-in.',
        'gift' => 'Upgrade paket Anda untuk mengakses fitur amplop digital.',
        'custom_music' => 'Upgrade paket Anda untuk menggunakan musik kustom.',
        'custom_domain' => 'Upgrade paket Anda untuk menggunakan domain kustom.',
    ];
    $displayMessage = $message ?? ($defaultMessages[$feature] ?? 'Fitur ini memerlukan paket yang lebih tinggi.');
@endphp

@if($compact)
    {{-- Compact inline locked state --}}
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 text-gray-500']) }}>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
        </svg>
        <span class="text-sm">{{ $title }}</span>
        <a href="{{ route('pricing') }}" class="text-sm font-medium {{ $colors['icon'] }} hover:underline ml-auto">
            Upgrade
        </a>
    </div>
@else
    {{-- Full locked state card --}}
    <div {{ $attributes->merge(['class' => 'rounded-lg border border-gray-200 bg-white p-6 text-center']) }}>
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full {{ $colors['bg'] }} mb-4">
            @if($icon === 'lock')
                <svg class="h-6 w-6 {{ $colors['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            @elseif($icon === 'chart')
                <svg class="h-6 w-6 {{ $colors['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            @elseif($icon === 'qr')
                <svg class="h-6 w-6 {{ $colors['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5z" />
                </svg>
            @else
                <svg class="h-6 w-6 {{ $colors['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            @endif
        </div>
        
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
        <p class="text-sm text-gray-600 mb-4">{{ $displayMessage }}</p>
        
        <a href="{{ route('pricing') }}" 
           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ $colors['btn'] }} focus:outline-none focus:ring-2 focus:ring-offset-2">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
            Upgrade Sekarang
        </a>
    </div>
@endif
