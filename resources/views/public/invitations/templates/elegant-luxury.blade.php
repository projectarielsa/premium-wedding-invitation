{{--
    Template: Elegant Luxury
    Style: Dark / Black / Gold with cinematic opening
    Typography: Premium serif with elegant accents
--}}
<x-layouts.public-invitation :invitation="$invitation" :guest="$guest" :isPreview="$isPreview">
    {{-- Opening Cover --}}
    @section('cover')
    <div class="fixed inset-0 bg-charcoal-950 flex flex-col items-center justify-center text-center px-6 overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M30 0L60 30L30 60L0 30z\' fill=\'none\' stroke=\'%23D4AF37\' stroke-width=\'0.5\'/%3E%3C/svg%3E'); background-size: 60px 60px;"></div>
        </div>
        
        {{-- Decorative Top Line --}}
        <div class="absolute top-8 left-1/2 -translate-x-1/2">
            <div class="w-px h-20 bg-gradient-to-b from-transparent via-gold-500 to-transparent"></div>
        </div>
        
        {{-- Main Content --}}
        <div class="relative z-10 max-w-lg mx-auto">
            {{-- Invitation Label --}}
            <p class="text-gold-500 text-sm tracking-[0.3em] uppercase mb-6 animate-fade-in-up opacity-0" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
                Wedding Invitation
            </p>
            
            {{-- Couple Names --}}
            <div class="mb-8 animate-fade-in-up opacity-0" style="animation-delay: 0.4s; animation-fill-mode: forwards;">
                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl text-white font-bold leading-tight">
                    {{ $invitation->bride_name }}
                </h1>
                <div class="flex items-center justify-center my-4">
                    <div class="w-12 h-px bg-gold-500"></div>
                    <span class="mx-4 font-accent text-gold-400 text-3xl italic">&</span>
                    <div class="w-12 h-px bg-gold-500"></div>
                </div>
                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl text-white font-bold leading-tight">
                    {{ $invitation->groom_name }}
                </h1>
            </div>
            
            {{-- Date --}}
            @if($invitation->event_date)
            <p class="text-ivory-300 text-lg tracking-wide animate-fade-in-up opacity-0" style="animation-delay: 0.6s; animation-fill-mode: forwards;">
                {{ $invitation->event_date->format('l, j F Y') }}
            </p>
            @endif
            
            {{-- Guest Personalization --}}
            @if($guest)
            <div class="mt-8 animate-fade-in-up opacity-0" style="animation-delay: 0.8s; animation-fill-mode: forwards;">
                <p class="text-gold-400/80 text-sm tracking-wider uppercase mb-2">Dear</p>
                <p class="text-white text-xl font-display">{{ $guest->name }}</p>
            </div>
            @endif
            
            {{-- Open Button --}}
            <button 
                x-on:click="openInvitation()"
                class="mt-12 group relative px-10 py-4 bg-transparent border-2 border-gold-500 text-gold-400 font-medium tracking-wider uppercase text-sm rounded-full overflow-hidden transition-all duration-500 hover:text-charcoal-900 animate-fade-in-up opacity-0"
                style="animation-delay: 1s; animation-fill-mode: forwards;"
            >
                <span class="relative z-10 flex items-center gap-2">
                    <span>Open Invitation</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
                <div class="absolute inset-0 bg-gold-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
            </button>
        </div>
        
        {{-- Decorative Bottom Line --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2">
            <div class="w-px h-20 bg-gradient-to-t from-transparent via-gold-500 to-transparent"></div>
        </div>
    </div>
    @endsection

    {{-- Main Invitation Content --}}
    <div class="bg-charcoal-950 text-white min-h-screen">
        
        {{-- Hero Section --}}
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
            {{-- Background Image --}}
            @if($invitation->cover_image_url)
            <div class="absolute inset-0">
                <img 
                    src="{{ $invitation->cover_image_url }}" 
                    alt="Cover"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-b from-charcoal-950/80 via-charcoal-950/60 to-charcoal-950"></div>
            </div>
            @else
            <div class="absolute inset-0 bg-gradient-to-b from-charcoal-900 to-charcoal-950"></div>
            @endif
            
            {{-- Content --}}
            <div class="relative z-10 text-center px-6 py-20 max-w-4xl mx-auto">
                {{-- Decorative Element --}}
                <div class="mb-8 opacity-60">
                    <svg class="w-16 h-16 mx-auto text-gold-500" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M50 10c-5 15-20 25-40 25 10 20 15 40 40 55 25-15 30-35 40-55-20 0-35-10-40-25z"/>
                    </svg>
                </div>
                
                <p class="text-gold-400 tracking-[0.4em] uppercase text-sm mb-6">The Wedding of</p>
                
                <h1 class="font-display text-6xl md:text-7xl lg:text-8xl font-bold text-white text-shadow-lg mb-4">
                    {{ $invitation->bride_name }}
                </h1>
                <div class="flex items-center justify-center my-6">
                    <div class="w-16 h-px bg-gradient-to-r from-transparent via-gold-500 to-transparent"></div>
                    <span class="mx-6 font-accent text-gold-400 text-4xl italic">&</span>
                    <div class="w-16 h-px bg-gradient-to-r from-transparent via-gold-500 to-transparent"></div>
                </div>
                <h1 class="font-display text-6xl md:text-7xl lg:text-8xl font-bold text-white text-shadow-lg">
                    {{ $invitation->groom_name }}
                </h1>
                
                {{-- Scroll Indicator --}}
                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
                    <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
            </div>
        </section>

        {{-- Countdown Section --}}
        @if($invitation->event_date && $invitation->countdown_enabled)
        <section class="py-20 px-6 bg-charcoal-900 reveal-section">
            <div class="max-w-4xl mx-auto text-center">
                <p class="text-gold-400 tracking-[0.3em] uppercase text-sm mb-4">Save The Date</p>
                <h2 class="font-display text-3xl md:text-4xl text-white mb-2">{{ $invitation->event_date->format('l') }}</h2>
                <p class="text-ivory-300 text-xl mb-12">{{ $invitation->event_date->format('j F Y') }}</p>
                
                @include('public.invitations.partials.countdown', ['theme' => 'gold', 'size' => 'large'])
            </div>
        </section>
        @endif

        {{-- Couple Section --}}
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-sm mb-4">The Happy Couple</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Bride & Groom</h2>
                </div>
                
                <div class="grid md:grid-cols-2 gap-12 md:gap-16">
                    {{-- Bride --}}
                    <div class="text-center">
                        <div class="w-48 h-48 mx-auto mb-6 rounded-full border-4 border-gold-500/30 p-2 bg-charcoal-800">
                            <div class="w-full h-full rounded-full bg-gradient-to-br from-gold-500/20 to-gold-600/10 flex items-center justify-center">
                                <span class="font-display text-5xl text-gold-400">{{ substr($invitation->bride_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h3 class="font-display text-3xl text-white mb-2">{{ $invitation->bride_name }}</h3>
                        @if($invitation->bride_parent)
                        <p class="text-ivory-400 text-sm">Daughter of</p>
                        <p class="text-ivory-300">{{ $invitation->bride_parent }}</p>
                        @endif
                    </div>
                    
                    {{-- Groom --}}
                    <div class="text-center">
                        <div class="w-48 h-48 mx-auto mb-6 rounded-full border-4 border-gold-500/30 p-2 bg-charcoal-800">
                            <div class="w-full h-full rounded-full bg-gradient-to-br from-gold-500/20 to-gold-600/10 flex items-center justify-center">
                                <span class="font-display text-5xl text-gold-400">{{ substr($invitation->groom_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h3 class="font-display text-3xl text-white mb-2">{{ $invitation->groom_name }}</h3>
                        @if($invitation->groom_parent)
                        <p class="text-ivory-400 text-sm">Son of</p>
                        <p class="text-ivory-300">{{ $invitation->groom_parent }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Opening Message --}}
        @if($invitation->opening_message)
        <section class="py-20 px-6 bg-charcoal-900 reveal-section">
            <div class="max-w-3xl mx-auto text-center">
                <svg class="w-12 h-12 mx-auto mb-8 text-gold-500/50" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
                <p class="font-accent text-xl md:text-2xl text-ivory-200 italic leading-relaxed">
                    {{ $invitation->opening_message }}
                </p>
            </div>
        </section>
        @endif

        {{-- Events Section --}}
        @if($invitation->events->count() > 0)
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-sm mb-4">Ceremony Details</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Wedding Events</h2>
                </div>
                
                <div class="space-y-8">
                    @foreach($invitation->events as $event)
                    <div class="bg-charcoal-800/50 border border-charcoal-700 rounded-2xl p-6 md:p-8 backdrop-blur-sm">
                        <div class="flex flex-col md:flex-row md:items-center gap-6">
                            {{-- Event Icon --}}
                            <div class="w-16 h-16 rounded-xl bg-gold-500/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-gold-400 text-2xl">{!! $event->type_icon !!}</span>
                            </div>
                            
                            {{-- Event Details --}}
                            <div class="flex-1">
                                <span class="inline-block px-3 py-1 bg-gold-500/20 text-gold-400 text-xs uppercase tracking-wider rounded-full mb-2">
                                    {{ $event->type_label }}
                                </span>
                                <h3 class="font-display text-2xl text-white mb-1">{{ $event->name }}</h3>
                                <p class="text-ivory-300">{{ $event->formatted_date }}</p>
                                @if($event->formatted_time)
                                <p class="text-ivory-400 text-sm">{{ $event->formatted_time }}</p>
                                @endif
                            </div>
                            
                            {{-- Venue & Map --}}
                            <div class="md:text-right">
                                @if($event->venue_name)
                                <p class="text-white font-medium">{{ $event->venue_name }}</p>
                                @endif
                                @if($event->venue_address)
                                <p class="text-ivory-400 text-sm mb-3">{{ $event->venue_address }}</p>
                                @endif
                                @if($event->google_maps_url)
                                <button 
                                    x-on:click="openMaps('{{ $event->google_maps_url }}')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gold-500 text-charcoal-900 font-medium text-sm rounded-lg hover:bg-gold-400 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    View Map
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        @if($event->dress_code)
                        <div class="mt-4 pt-4 border-t border-charcoal-700">
                            <p class="text-ivory-400 text-sm"><span class="text-gold-400">Dress Code:</span> {{ $event->dress_code }}</p>
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
        <section class="py-20 px-6 bg-charcoal-900 reveal-section">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-sm mb-4">Our Journey</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Love Story</h2>
                </div>
                
                <div class="relative">
                    {{-- Timeline Line --}}
                    <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-gold-500 via-gold-500/50 to-transparent"></div>
                    
                    {{-- Timeline Items --}}
                    <div class="space-y-12">
                        @foreach($invitation->story_section as $index => $story)
                        <div class="relative flex items-start gap-8 {{ $index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' }}">
                            {{-- Dot --}}
                            <div class="absolute left-4 md:left-1/2 w-3 h-3 bg-gold-500 rounded-full transform -translate-x-1/2 mt-2"></div>
                            
                            {{-- Content --}}
                            <div class="ml-12 md:ml-0 md:w-1/2 {{ $index % 2 === 0 ? 'md:pr-12 md:text-right' : 'md:pl-12' }}">
                                <span class="inline-block px-3 py-1 bg-gold-500/20 text-gold-400 text-xs uppercase tracking-wider rounded-full mb-2">
                                    {{ $story['date'] ?? 'Memory' }}
                                </span>
                                <h3 class="font-display text-xl text-white mb-2">{{ $story['title'] ?? '' }}</h3>
                                <p class="text-ivory-400">{{ $story['description'] ?? '' }}</p>
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
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-sm mb-4">Captured Moments</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Our Gallery</h2>
                </div>
                
                @include('public.invitations.partials.gallery', [
                    'images' => $invitation->gallery,
                    'theme' => 'dark',
                    'columns' => 3
                ])
            </div>
        </section>
        @endif

        {{-- RSVP Section --}}
        @if($invitation->rsvp_enabled)
        <section id="rsvp" class="py-20 px-6 bg-charcoal-900 reveal-section">
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-sm mb-4">Be Our Guest</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">RSVP</h2>
                    <p class="text-ivory-400 mt-4">We would be honored by your presence</p>
                </div>
                
                @include('public.invitations.partials.rsvp-form', [
                    'invitation' => $invitation,
                    'guest' => $guest,
                    'theme' => 'gold',
                    'maxAttendees' => $guest?->max_attendees ?? $invitation->max_attendance_per_guest
                ])
            </div>
        </section>
        @endif

        {{-- Gift Section --}}
        @if($invitation->gift_enabled)
        <section id="gift" class="py-20 px-6 reveal-section">
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-sm mb-4">Wedding Gift</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Amplop Digital</h2>
                    <p class="text-ivory-400 mt-4">Your presence is our greatest gift, but if you wish to honor us with more</p>
                </div>
                
                @include('public.invitations.partials.gift-section', [
                    'invitation' => $invitation,
                    'theme' => 'gold'
                ])
            </div>
        </section>
        @endif

        {{-- Share Section --}}
        <section class="py-16 px-6 bg-charcoal-900 reveal-section">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="font-display text-2xl text-white mb-6">Share This Invitation</h3>
                <div class="flex items-center justify-center gap-4">
                    <button 
                        x-on:click="shareToWhatsApp()"
                        class="flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-colors"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Share via WhatsApp
                    </button>
                    <button 
                        x-on:click="copyToClipboard(window.location.href, 'link-copy-feedback')"
                        class="flex items-center gap-2 px-6 py-3 bg-charcoal-700 hover:bg-charcoal-600 text-white rounded-xl transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy Link
                    </button>
                </div>
                <p id="link-copy-feedback" class="hidden mt-3 text-emerald-400 text-sm">Link copied to clipboard!</p>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-12 px-6 border-t border-charcoal-800">
            <div class="max-w-4xl mx-auto text-center">
                {{-- Couple Names --}}
                <h2 class="font-display text-3xl text-white mb-4">
                    {{ $invitation->bride_name }} <span class="text-gold-400">&</span> {{ $invitation->groom_name }}
                </h2>
                
                {{-- Closing Message --}}
                <p class="text-ivory-400 italic mb-8">
                    Thank you for being part of our special day
                </p>
                
                {{-- Decorative Heart --}}
                <div class="mb-8">
                    <svg class="w-8 h-8 mx-auto text-gold-500 animate-heartbeat" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                
                {{-- Branding --}}
                <p class="text-charcoal-600 text-xs">
                    Created with <span class="text-gold-500">Wedding Invite</span>
                </p>
            </div>
        </footer>
    </div>
</x-layouts.public-invitation>
