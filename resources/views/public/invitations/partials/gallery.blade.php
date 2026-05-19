{{-- Gallery Component --}}
@props([
    'images' => [],
    'theme' => 'light', // light, dark
    'columns' => 3
])

@if(count($images) > 0)
<div 
    x-data="{ 
        lightboxOpen: false, 
        currentImage: 0,
        images: @js(collect($images)->map(fn($img) => str_starts_with($img, 'http') ? $img : asset('storage/' . $img))->values()->toArray()),
        openLightbox(index) {
            this.currentImage = index;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightboxOpen = false;
            document.body.style.overflow = '';
        },
        nextImage() {
            this.currentImage = (this.currentImage + 1) % this.images.length;
        },
        prevImage() {
            this.currentImage = (this.currentImage - 1 + this.images.length) % this.images.length;
        }
    }"
    x-on:keydown.escape.window="closeLightbox()"
    x-on:keydown.arrow-right.window="lightboxOpen && nextImage()"
    x-on:keydown.arrow-left.window="lightboxOpen && prevImage()"
    {{ $attributes }}
    x-intersect.once="$dispatch('gallery-visible')"
    x-on:gallery-visible="$el.querySelectorAll('.gallery-item').forEach((el, i) => setTimeout(() => el.classList.add('revealed'), i * 100))"
>
    {{-- Gallery Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-{{ $columns }} gap-3 md:gap-4">
        @foreach($images as $index => $image)
            @php
                $imageUrl = str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
            @endphp
            <div 
                x-on:click="openLightbox({{ $index }})"
                class="gallery-item relative aspect-square overflow-hidden rounded-xl cursor-pointer group opacity-0 translate-y-4 transition-all duration-500"
                style="transition-delay: {{ $index * 100 }}ms"
            >
                <img 
                    src="{{ $imageUrl }}" 
                    alt="Gallery image {{ $index + 1 }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                    loading="lazy"
                >
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform scale-75 group-hover:scale-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                    </svg>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Lightbox Modal --}}
    <div 
        x-show="lightboxOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center"
        x-cloak
    >
        {{-- Close Button --}}
        <button 
            x-on:click="closeLightbox()"
            class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Previous Button --}}
        <button 
            x-on:click="prevImage()"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Next Button --}}
        <button 
            x-on:click="nextImage()"
            class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Image --}}
        <div class="max-w-5xl max-h-[85vh] px-4">
            <img 
                :src="images[currentImage]" 
                alt="Gallery image"
                class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
            >
        </div>

        {{-- Image Counter --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 px-4 py-2 bg-white/10 rounded-full text-white text-sm">
            <span x-text="currentImage + 1"></span> / <span x-text="images.length"></span>
        </div>
    </div>
</div>
@else
    {{-- Empty Gallery State --}}
    <div class="text-center py-8 opacity-50">
        <svg class="w-12 h-12 mx-auto mb-3 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="text-sm">No gallery images available</p>
    </div>
@endif

<style>
.gallery-item.revealed {
    opacity: 1;
    transform: translateY(0);
}
</style>
