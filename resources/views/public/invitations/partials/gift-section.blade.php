{{-- Gift Section Component --}}
@props([
    'invitation',
    'theme' => 'light' // light, dark, gold
])

@php
    $giftAccounts = $invitation->giftAccounts()->active()->ordered()->get();
    
    $themes = [
        'light' => [
            'card' => 'bg-white border border-ivory-200 shadow-soft',
            'title' => 'text-charcoal-800',
            'text' => 'text-charcoal-600',
            'muted' => 'text-charcoal-400',
            'divider' => 'border-ivory-200',
            'input' => 'bg-ivory-100 border-ivory-200 text-charcoal-800',
            'button' => 'bg-gold-500 hover:bg-gold-600 text-white',
            'buttonOutline' => 'border-gold-500 text-gold-600 hover:bg-gold-50'
        ],
        'dark' => [
            'card' => 'bg-charcoal-800/90 border border-charcoal-700 shadow-xl backdrop-blur-sm',
            'title' => 'text-white',
            'text' => 'text-charcoal-300',
            'muted' => 'text-charcoal-500',
            'divider' => 'border-charcoal-700',
            'input' => 'bg-charcoal-900 border-charcoal-600 text-white',
            'button' => 'bg-gold-500 hover:bg-gold-400 text-charcoal-900',
            'buttonOutline' => 'border-gold-400 text-gold-400 hover:bg-gold-400/10'
        ],
        'gold' => [
            'card' => 'bg-gradient-to-br from-charcoal-900 to-charcoal-800 border border-gold-500/30 shadow-gold',
            'title' => 'text-gold-400',
            'text' => 'text-ivory-200',
            'muted' => 'text-ivory-400',
            'divider' => 'border-gold-500/30',
            'input' => 'bg-charcoal-800 border-gold-500/30 text-ivory-100',
            'button' => 'bg-gold-500 hover:bg-gold-400 text-charcoal-900',
            'buttonOutline' => 'border-gold-500 text-gold-400 hover:bg-gold-500/10'
        ]
    ];
    
    $t = $themes[$theme] ?? $themes['light'];
@endphp

@if($giftAccounts->count() > 0)
<div 
    {{ $attributes->merge(['class' => 'space-y-6']) }}
    x-intersect.once="trackGiftView()"
>
    @foreach($giftAccounts as $account)
        <div class="{{ $t['card'] }} rounded-2xl overflow-hidden">
            {{-- Account Header --}}
            <div class="p-5 md:p-6">
                <div class="flex items-center gap-4 mb-4">
                    {{-- Provider Logo/Icon --}}
                    @if($account->provider_logo_url)
                        <img 
                            src="{{ $account->provider_logo_url }}" 
                            alt="{{ $account->provider }}"
                            class="w-12 h-12 object-contain rounded-lg"
                        >
                    @else
                        <div class="w-12 h-12 rounded-lg bg-gold-500/10 flex items-center justify-center">
                            @switch($account->type->value)
                                @case('bank_transfer')
                                    <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    @break
                                @case('e_wallet')
                                    <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @break
                                @case('qris')
                                    <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                    @endif
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold {{ $t['title'] }}">{{ $account->provider }}</h4>
                        <p class="text-sm {{ $t['muted'] }}">{{ $account->type_label }}</p>
                    </div>
                </div>
                
                {{-- Account Details --}}
                @if($account->account_number)
                    <div class="mb-4">
                        <p class="text-xs {{ $t['muted'] }} mb-1 uppercase tracking-wider">Account Number</p>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 {{ $t['input'] }} px-4 py-3 rounded-xl font-mono text-lg tracking-wider truncate">
                                {{ $account->account_number }}
                            </div>
                            <button 
                                x-on:click="copyToClipboard('{{ $account->account_number }}', 'copy-feedback-{{ $account->id }}')"
                                class="{{ $t['button'] }} px-4 py-3 rounded-xl font-medium transition-all duration-200 hover:scale-105 active:scale-95 flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span class="hidden sm:inline">Copy</span>
                            </button>
                        </div>
                        {{-- Copy Feedback --}}
                        <p id="copy-feedback-{{ $account->id }}" class="hidden mt-2 text-sm text-emerald-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                            Copied to clipboard!
                        </p>
                    </div>
                @endif
                
                {{-- Account Holder --}}
                <div class="mb-4">
                    <p class="text-xs {{ $t['muted'] }} mb-1 uppercase tracking-wider">Account Holder</p>
                    <p class="font-medium {{ $t['title'] }}">{{ $account->account_holder }}</p>
                </div>
                
                {{-- QR Code --}}
                @if($account->qr_image_url)
                    <div class="border-t {{ $t['divider'] }} pt-5 mt-5">
                        <p class="text-xs {{ $t['muted'] }} mb-3 uppercase tracking-wider text-center">Scan QR Code</p>
                        <div class="flex justify-center">
                            <div class="bg-white p-4 rounded-xl inline-block shadow-inner">
                                <img 
                                    src="{{ $account->qr_image_url }}" 
                                    alt="QR Code for {{ $account->provider }}"
                                    class="w-40 h-40 md:w-48 md:h-48 object-contain"
                                >
                            </div>
                        </div>
                    </div>
                @endif
                
                {{-- Instructions --}}
                @if($account->instructions)
                    <div class="border-t {{ $t['divider'] }} pt-4 mt-4">
                        <p class="text-sm {{ $t['text'] }}">{{ $account->instructions }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
    
    {{-- Thank You Message --}}
    <div class="text-center pt-4">
        <p class="text-sm {{ $theme === 'dark' || $theme === 'gold' ? 'text-ivory-300' : 'text-charcoal-500' }} italic">
            Your generous gift means the world to us. Thank you for being part of our special day.
        </p>
    </div>
</div>
@else
    {{-- Empty Gift Section --}}
    <div class="text-center py-8">
        <svg class="w-16 h-16 mx-auto mb-4 {{ $theme === 'dark' || $theme === 'gold' ? 'text-charcoal-600' : 'text-ivory-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
        </svg>
        <p class="{{ $theme === 'dark' || $theme === 'gold' ? 'text-charcoal-400' : 'text-charcoal-500' }}">Gift information will be available soon.</p>
    </div>
@endif
