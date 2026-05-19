{{-- Floating WhatsApp Button --}}
@props([
    'phone' => '6281234567890',
    'message' => 'Halo, saya tertarik membuat undangan digital premium.'
])

<div 
    x-data="{ 
        isVisible: false,
        showTooltip: false,
        init() {
            setTimeout(() => this.isVisible = true, 2000);
            // Auto show tooltip after 5 seconds
            setTimeout(() => {
                this.showTooltip = true;
                setTimeout(() => this.showTooltip = false, 5000);
            }, 5000);
        }
    }"
    x-show="isVisible"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-8 scale-75"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-8 scale-75"
    class="fixed bottom-6 right-6 z-50"
    x-cloak
>
    {{-- Tooltip --}}
    <div 
        x-show="showTooltip"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-4"
        class="absolute bottom-full right-0 mb-3 w-64 bg-white rounded-2xl shadow-soft-xl p-4 border border-ivory-200"
        x-cloak
    >
        <button 
            @click="showTooltip = false" 
            class="absolute top-2 right-2 p-1 text-charcoal-400 hover:text-charcoal-600 rounded-full hover:bg-ivory-100"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <p class="text-sm text-charcoal-700 font-medium mb-1">Butuh bantuan?</p>
        <p class="text-xs text-charcoal-500">Chat langsung dengan tim kami via WhatsApp!</p>
        <div class="absolute bottom-0 right-6 translate-y-1/2 rotate-45 w-3 h-3 bg-white border-r border-b border-ivory-200"></div>
    </div>

    {{-- Main Button --}}
    <a 
        href="https://wa.me/{{ $phone }}?text={{ urlencode($message) }}"
        target="_blank"
        rel="noopener noreferrer"
        @click="$fetch('/api/track-whatsapp-click', { method: 'POST' }).catch(() => {})"
        @mouseenter="showTooltip = true"
        @mouseleave="showTooltip = false"
        class="group flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110"
        aria-label="Chat via WhatsApp"
    >
        {{-- Pulse Ring --}}
        <span class="absolute w-full h-full rounded-full bg-emerald-400 animate-ping opacity-20"></span>
        <span class="absolute w-full h-full rounded-full bg-emerald-400 animate-pulse opacity-10"></span>
        
        {{-- WhatsApp Icon --}}
        <svg class="w-8 h-8 text-white relative z-10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
    </a>

    {{-- Badge --}}
    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold animate-bounce">
        1
    </span>
</div>
