@props([
    'template' => null,
    'name' => 'Template Premium',
    'thumbnail' => null,
])

<div {{ $attributes->merge(['class' => 'relative group rounded-lg overflow-hidden border border-gray-200 bg-gray-50']) }}>
    {{-- Thumbnail with overlay --}}
    <div class="aspect-[3/4] relative">
        @if($thumbnail)
            <img src="{{ $thumbnail }}" alt="{{ $name }}" class="w-full h-full object-cover filter grayscale opacity-50">
        @else
            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
        @endif
        
        {{-- Lock overlay --}}
        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center">
            <div class="w-12 h-12 rounded-full bg-white/90 flex items-center justify-center mb-2">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <span class="text-white font-medium text-sm">Premium</span>
        </div>
    </div>
    
    {{-- Info --}}
    <div class="p-3">
        <h4 class="font-medium text-gray-900 truncate">{{ $template?->name ?? $name }}</h4>
        <div class="mt-2 flex items-center justify-between">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                </svg>
                Premium
            </span>
            <a href="{{ route('pricing') }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">
                Unlock
            </a>
        </div>
    </div>
</div>
