{{--
    Template: Minimal White
    Style: Ivory / Soft White / Champagne with clean modern romantic feel
    Typography: Elegant sans-serif with refined accents
--}}
<x-layouts.public-invitation :invitation="$invitation" :guest="$guest" :isPreview="$isPreview">
    {{-- Opening Cover --}}
    @section('cover')
    <div class="fixed inset-0 bg-ivory-100 flex flex-col items-center justify-center text-center px-6 overflow-hidden">
        {{-- Soft Gradient Background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-ivory-50 via-champagne-100 to-ivory-200"></div>
        
        {{-- Decorative Botanical Pattern --}}
        <div class="absolute inset-0 opacity-[0.03]">
            <div class="absolute top-0 left-0 w-64 h-64 -translate-x-1/2 -translate-y-1/2">
                <svg viewBox="0 0 200 200" fill="none" stroke="currentColor" class="w-full h-full text-charcoal-900">
                    <circle cx="100" cy="100" r="80" stroke-width="0.5"/>
                    <circle cx="100" cy="100" r="60" stroke-width="0.5"/>
                    <circle cx="100" cy="100" r="40" stroke-width="0.5"/>
                </svg>
            </div>
            <div class="absolute bottom-0 right-0 w-80 h-80 translate-x-1/4 translate-y-1/4">
                <svg viewBox="0 0 200 200" fill="none" stroke="currentColor" class="w-full h-full text-charcoal-900">
                    <circle cx="100" cy="100" r="80" stroke-width="0.5"/>
                    <circle cx="100" cy="100" r="60" stroke-width="0.5"/>
                </svg>
            </div>
        </div>
        
        {{-- Main Content --}}
        <div class="relative z-10 max-w-lg mx-auto">
            {{-- Invitation Label --}}
            <p class="text-charcoal-500 text-xs tracking-[0.4em] uppercase mb-8 animate-fade-in-up opacity-0" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
                You Are Cordially Invited
            </p>
            
            {{-- Couple Names --}}
            <div class="mb-8 animate-fade-in-up opacity-0" style="animation-delay: 0.4s; animation-fill-mode: forwards;">
                <h1 class="font-accent text-5xl md:text-6xl text-charcoal-800 font-medium leading-tight">
                    {{ $invitation->bride_name }}
                </h1>
                <div class="flex items-center justify-center my-5">
                    <div class="w-8 h-px bg-gold-400"></div>
                    <span class="mx-4 font-accent text-gold-500 text-2xl">&</span>
                    <div class="w-8 h-px bg-gold-400"></div>
                </div>
                <h1 class="font-accent text-5xl md:text-6xl text-charcoal-800 font-medium leading-tight">
                    {{ $invitation->groom_name }}
                </h1>
            </div>
            
            {{-- Date --}}
            @if($invitation->event_date)
            <p class="text-charcoal-600 text-sm tracking-wider animate-fade-in-up opacity-0" style="animation-delay: 0.6s; animation-fill-mode: forwards;">
                {{ $invitation->event_date->format('d . m . Y') }}
            </p>
            @endif
            
            {{-- Guest Personalization --}}
            @if($guest)
            <div class="mt-10 animate-fade-in-up opacity-0" style="animation-delay: 0.8s; animation-fill-mode: forwards;">
                <p class="text-charcoal-400 text-xs tracking-wider uppercase mb-1">To</p>
                <p class="text-charcoal-700 font-accent text-lg">{{ $guest->name }}</p>
            </div>
            @endif
            
            {{-- Open Button --}}
            <button 
                x-on:click="openInvitation()"
                class="mt-12 group px-8 py-3 bg-charcoal-800 text-white font-medium tracking-wider text-sm rounded-full transition-all duration-300 hover:bg-charcoal-700 hover:shadow-lg animate-fade-in-up opacity-0"
                style="animation-delay: 1s; animation-fill-mode: forwards;"
            >
                <span class="flex items-center gap-2">
                    <span>Open Invitation</span>
                    <svg class="w-4 h-4 transform group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </button>
        </div>
        
        {{-- Bottom Decorative Element --}}
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-fade-in-up opacity-0" style="animation-delay: 1.2s; animation-fill-mode: forwards;">
            <svg class="w-6 h-6 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </div>
    </div>
    @endsection

    {{-- Main Invitation Content --}}
    <div class="bg-ivory-100 text-charcoal-800 min-h-screen">
        
        {{-- Hero Section --}}
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
            {{-- Background --}}
            @if($invitation->cover_image_url)
            <div class="absolute inset-0">
                <img 
                    src="{{ $invitation->cover_image_url }}" 
                    alt="Cover"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-b from-ivory-100/90 via-ivory-100/70 to-ivory-100"></div>
            </div>
            @else
            <div class="absolute inset-0 bg-gradient-to-b from-champagne-100 via-ivory-100 to-ivory-200"></div>
            @endif
            
            {{-- Content --}}
            <div class="relative z-10 text-center px-6 py-20 max-w-4xl mx-auto">
                <p class="text-charcoal-500 tracking-[0.5em] uppercase text-xs mb-8">The Wedding Of</p>
                
                <h1 class="font-accent text-6xl md:text-7xl lg:text-8xl text-charcoal-800 mb-4">
                    {{ $invitation->bride_name }}
                </h1>
                <div class="flex items-center justify-center my-6">
                    <div class="w-20 h-px bg-gradient-to-r from-transparent via-gold-400 to-transparent"></div>
                </div>
                <h1 class="font-accent text-6xl md:text-7xl lg:text-8xl text-charcoal-800">
                    {{ $invitation->groom_name }}
                </h1>
                
                @if($invitation->event_date)
                <p class="mt-10 text-charcoal-600 tracking-[0.3em] text-sm">
                    {{ $invitation->event_date->format('F j, Y') }}
                </p>
                @endif
                
                {{-- Scroll Indicator --}}
                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
                    <div class="w-6 h-10 border-2 border-charcoal-400 rounded-full flex justify-center pt-2">
                        <div class="w-1 h-2 bg-charcoal-400 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Countdown Section --}}
        @if($invitation->event_date && $invitation->countdown_enabled)
        <section class="py-24 px-6 bg-white reveal-section">
            <div class="max-w-4xl mx-auto text-center">
                <p class="text-charcoal-500 tracking-[0.3em] uppercase text-xs mb-6">Counting Down To</p>
                <h2 class="font-accent text-3xl md:text-4xl text-charcoal-800 mb-4">Our Special Day</h2>
                <p class="text-charcoal-600 mb-12">{{ $invitation->event_date->format('l, F j, Y') }}</p>
                
                @include('public.invitations.partials.countdown', ['theme' => 'light', 'size' => 'large'])
            </div>
        </section>
        @endif

        {{-- Couple Section --}}
        <section class="py-24 px-6 reveal-section">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-charcoal-500 tracking-[0.3em] uppercase text-xs mb-4">Meet</p>
                    <h2 class="font-accent text-4xl md:text-5xl text-charcoal-800">The Couple</h2>
                </div>
                
                <div class="grid md:grid-cols-2 gap-16 items-center">
                    {{-- Bride --}}
                    <div class="text-center">
                        <div class="w-56 h-56 mx-auto mb-8 rounded-full border border-ivory-300 p-3 bg-white shadow-soft">
                            <div class="w-full h-full rounded-full bg-gradient-to-br from-champagne-100 to-ivory-200 flex items-center justify-center">
                                <span class="font-accent text-6xl text-charcoal-400">{{ substr($invitation->bride_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h3 class="font-accent text-3xl text-charcoal-800 mb-2">{{ $invitation->bride_name }}</h3>
                        @if($invitation->bride_parent)
                        <p class="text-charcoal-500 text-sm mb-1">Daughter of</p>
                        <p class="text-charcoal-600">{{ $invitation->bride_parent }}</p>
                        @endif
                    </div>
                    
                    {{-- Groom --}}
                    <div class="text-center">
                        <div class="w-56 h-56 mx-auto mb-8 rounded-full border border-ivory-300 p-3 bg-white shadow-soft">
                            <div class="w-full h-full rounded-full bg-gradient-to-br from-champagne-100 to-ivory-200 flex items-center justify-center">
                                <span class="font-accent text-6xl text-charcoal-400">{{ substr($invitation->groom_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h3 class="font-accent text-3xl text-charcoal-800 mb-2">{{ $invitation->groom_name }}</h3>
                        @if($invitation->groom_parent)
                        <p class="text-charcoal-500 text-sm mb-1">Son of</p>
                        <p class="text-charcoal-600">{{ $invitation->groom_parent }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Opening Message --}}
        @if($invitation->opening_message)
        <section class="py-24 px-6 bg-white reveal-section">
            <div class="max-w-3xl mx-auto text-center">
                <div class="mb-8">
                    <div class="inline-flex items-center gap-4">
                        <div class="w-12 h-px bg-gold-400"></div>
                        <svg class="w-5 h-5 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <div class="w-12 h-px bg-gold-400"></div>
                    </div>
                </div>
                <p class="font-accent text-xl md:text-2xl text-charcoal-600 italic leading-relaxed">
                    "{{ $invitation->opening_message }}"
                </p>
            </div>
        </section>
        @endif

        {{-- Events Section --}}
        @if($invitation->events->count() > 0)
        <section class="py-24 px-6 reveal-section">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-charcoal-500 tracking-[0.3em] uppercase text-xs mb-4">Save The Date</p>
                    <h2 class="font-accent text-4xl md:text-5xl text-charcoal-800">Event Details</h2>
                </div>
                
                <div class="space-y-6">
                    @foreach($invitation->events as $event)
                    <div class="bg-white rounded-3xl border border-ivory-200 p-8 shadow-soft">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                            {{-- Event Type Badge --}}
                            <div class="lg:w-32 text-center lg:text-left">
                                <span class="inline-block px-4 py-2 bg-champagne-100 text-charcoal-600 text-xs uppercase tracking-wider rounded-full">
                                    {{ $event->type_label }}
                                </span>
                            </div>
                            
                            {{-- Event Details --}}
                            <div class="flex-1 text-center lg:text-left">
                                <h3 class="font-accent text-2xl text-charcoal-800 mb-2">{{ $event->name }}</h3>
                                <p class="text-charcoal-600 mb-1">{{ $event->formatted_date }}</p>
                                @if($event->formatted_time)
                                <p class="text-charcoal-500 text-sm">{{ $event->formatted_time }}</p>
                                @endif
                            </div>
                            
                            {{-- Venue --}}
                            <div class="text-center lg:text-right lg:max-w-xs">
                                @if($event->venue_name)
                                <p class="text-charcoal-700 font-medium">{{ $event->venue_name }}</p>
                                @endif
                                @if($event->venue_address)
                                <p class="text-charcoal-500 text-sm mt-1">{{ $event->venue_address }}</p>
                                @endif
                                @if($event->google_maps_url)
                                <button 
                                    x-on:click="openMaps('{{ $event->google_maps_url }}')"
                                    class="mt-4 inline-flex items-center gap-2 px-5 py-2 border border-charcoal-300 text-charcoal-600 font-medium text-sm rounded-full hover:bg-charcoal-800 hover:text-white hover:border-charcoal-800 transition-all duration-300"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Open Maps
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        @if($event->dress_code)
                        <div class="mt-6 pt-6 border-t border-ivory-200 text-center">
                            <p class="text-charcoal-500 text-sm"><span class="font-medium">Dress Code:</span> {{ $event->dress_code }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- Love Story Timeline --}}
        @if($invitation->story_section && count($invitation->story_section) > 0)
        <section class="py-24 px-6 bg-white reveal-section">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-charcoal-500 tracking-[0.3em] uppercase text-xs mb-4">Our Journey</p>
                    <h2 class="font-accent text-4xl md:text-5xl text-charcoal-800">Love Story</h2>
                </div>
                
                <div class="relative">
                    {{-- Timeline Line --}}
                    <div class="absolute left-6 md:left-1/2 top-0 bottom-0 w-px bg-ivory-300"></div>
                    
                    {{-- Timeline Items --}}
                    <div class="space-y-12">
                        @foreach($invitation->story_section as $index => $story)
                        <div class="relative flex items-start gap-8 {{ $index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' }}">
                            {{-- Dot --}}
                            <div class="absolute left-6 md:left-1/2 w-3 h-3 bg-gold-400 rounded-full transform -translate-x-1/2 mt-2 ring-4 ring-ivory-100"></div>
                            
                            {{-- Content --}}
                            <div class="ml-14 md:ml-0 md:w-1/2 {{ $index % 2 === 0 ? 'md:pr-16 md:text-right' : 'md:pl-16' }}">
                                <div class="bg-white rounded-2xl border border-ivory-200 p-6 shadow-soft">
                                    <span class="text-gold-500 text-sm font-medium">{{ $story['date'] ?? 'Memory' }}</span>
                                    <h3 class="font-accent text-xl text-charcoal-800 mt-2 mb-3">{{ $story['title'] ?? '' }}</h3>
                                    <p class="text-charcoal-500 text-sm">{{ $story['description'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- Gallery Section --}}
        @if($invitation->gallery && count($invitation->gallery) > 0)
        <section class="py-24 px-6 reveal-section">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-charcoal-500 tracking-[0.3em] uppercase text-xs mb-4">Precious Moments</p>
                    <h2 class="font-accent text-4xl md:text-5xl text-charcoal-800">Gallery</h2>
                </div>
                
                @include('public.invitations.partials.gallery', [
                    'images' => $invitation->gallery,
                    'theme' => 'light',
                    'columns' => 3
                ])
            </div>
        </section>
        @endif

        {{-- RSVP Section --}}
        @if($invitation->rsvp_enabled)
        <section id="rsvp" class="py-24 px-6 bg-white reveal-section">
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-charcoal-500 tracking-[0.3em] uppercase text-xs mb-4">Your Response</p>
                    <h2 class="font-accent text-4xl md:text-5xl text-charcoal-800">RSVP</h2>
                    <p class="text-charcoal-500 mt-4">Kindly respond by letting us know your attendance</p>
                </div>
                
                @include('public.invitations.partials.rsvp-form', [
                    'invitation' => $invitation,
                    'guest' => $guest,
                    'theme' => 'light',
                    'maxAttendees' => $guest?->max_attendees ?? $invitation->max_attendance_per_guest
                ])
            </div>
        </section>
        @endif

        {{-- Gift Section --}}
        @if($invitation->gift_enabled)
        <section id="gift" class="py-24 px-6 reveal-section">
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-charcoal-500 tracking-[0.3em] uppercase text-xs mb-4">Gift Corner</p>
                    <h2 class="font-accent text-4xl md:text-5xl text-charcoal-800">Wedding Gift</h2>
                    <p class="text-charcoal-500 mt-4">Your presence at our wedding is the greatest gift of all. However, if you wish to honor us with a gift</p>
                </div>
                
                @include('public.invitations.partials.gift-section', [
                    'invitation' => $invitation,
                    'theme' => 'light'
                ])
            </div>
        </section>
        @endif

        {{-- Share Section --}}
        <section class="py-16 px-6 bg-white reveal-section">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="font-accent text-2xl text-charcoal-800 mb-6">Share This Invitation</h3>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <button 
                        x-on:click="shareToWhatsApp()"
                        class="flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full transition-colors"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </button>
                    <button 
                        x-on:click="copyToClipboard(window.location.href, 'link-copy-feedback')"
                        class="flex items-center gap-2 px-6 py-3 border border-charcoal-300 text-charcoal-600 hover:bg-charcoal-800 hover:text-white hover:border-charcoal-800 rounded-full transition-all duration-300"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy Link
                    </button>
                </div>
                <p id="link-copy-feedback" class="hidden mt-3 text-emerald-600 text-sm">Link copied!</p>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-16 px-6 border-t border-ivory-200">
            <div class="max-w-4xl mx-auto text-center">
                {{-- Couple Names --}}
                <h2 class="font-accent text-3xl text-charcoal-800 mb-4">
                    {{ $invitation->bride_name }} & {{ $invitation->groom_name }}
                </h2>
                
                {{-- Thank You --}}
                <p class="text-charcoal-500 italic mb-8">
                    With love and gratitude, we thank you for being part of our journey
                </p>
                
                {{-- Decorative Element --}}
                <div class="flex items-center justify-center gap-4 mb-8">
                    <div class="w-8 h-px bg-gold-400"></div>
                    <svg class="w-5 h-5 text-gold-400 animate-heartbeat" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <div class="w-8 h-px bg-gold-400"></div>
                </div>
                
                {{-- Branding --}}
                <p class="text-charcoal-400 text-xs">
                    Made with love by <span class="text-gold-500">Wedding Invite</span>
                </p>
            </div>
        </footer>
    </div>
</x-layouts.public-invitation>
