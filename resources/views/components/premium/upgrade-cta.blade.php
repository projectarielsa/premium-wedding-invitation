@props([
    'title' => 'Upgrade Paket Anda',
    'message' => 'Dapatkan akses ke fitur premium dan tingkatkan pengalaman undangan digital Anda.',
    'package' => null,
    'feature' => null,
    'variant' => 'default', // default, inline, banner
])

@php
    $featureLabels = [
        'invitations' => 'lebih banyak undangan',
        'guests' => 'kapasitas tamu lebih besar',
        'analytics' => 'fitur analitik lengkap',
        'export' => 'fitur export data',
        'qr_checkin' => 'fitur QR check-in',
        'gift' => 'fitur amplop digital',
        'custom_music' => 'musik kustom',
        'custom_domain' => 'domain kustom',
        'templates' => 'template premium eksklusif',
    ];
    $featureLabel = $feature ? ($featureLabels[$feature] ?? $feature) : null;
@endphp

@if($variant === 'banner')
    {{-- Full-width banner style --}}
    <div {{ $attributes->merge(['class' => 'bg-gradient-to-r from-amber-50 via-yellow-50 to-amber-50 border border-amber-200 rounded-lg p-4']) }}>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">{{ $title }}</h4>
                    <p class="text-sm text-gray-600 mt-0.5">
                        @if($package)
                            Upgrade ke <span class="font-medium">{{ $package->name }}</span> untuk {{ $featureLabel ?? 'fitur premium' }}.
                        @else
                            {{ $message }}
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('pricing') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                Lihat Paket
                <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
@elseif($variant === 'inline')
    {{-- Compact inline style --}}
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-sm']) }}>
        <span class="text-gray-600">{{ $message }}</span>
        <a href="{{ route('pricing') }}" class="font-medium text-amber-600 hover:text-amber-700 hover:underline">
            Upgrade &rarr;
        </a>
    </div>
@else
    {{-- Default card style --}}
    <div {{ $attributes->merge(['class' => 'bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-200 rounded-xl p-6']) }}>
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-900 text-lg">{{ $title }}</h4>
                <p class="text-gray-600 mt-1">
                    @if($package)
                        Upgrade ke <span class="font-medium">{{ $package->name }}</span> 
                        ({{ $package->formatted_price }}) untuk mendapatkan {{ $featureLabel ?? 'fitur premium' }}.
                    @else
                        {{ $message }}
                    @endif
                </p>
                <div class="mt-4">
                    <a href="{{ route('pricing') }}" 
                       class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        Upgrade Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
