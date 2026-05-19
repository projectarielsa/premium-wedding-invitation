{{--
    Template: Modern Dark
    Style: Dark glassmorphism with elegant motion and premium contrast
    Typography: Modern sans-serif with bold contrasts
--}}
<x-layouts.public-invitation :invitation="$invitation" :guest="$guest" :isPreview="$isPreview">
    {{-- Opening Cover --}}
    @section('cover')
    <div class="fixed inset-0 bg-charcoal-900 flex flex-col items-center justify-center text-center px-6 overflow-hidden">
        {{-- Animated Background Gradient --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-900/30 via-charcoal-900 to-blue-900/20"></div>
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-gold-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>


        {{-- Main Content --}}
        <div class="relative z-10 max-w-lg mx-auto">
            {{-- Glass Card --}}
            <div class="glass-dark rounded-3xl p-8 md:p-12 border border-white/10">
                {{-- Label --}}
                <div class="flex items-center justify-center gap-2 mb-8 animate-fade-in-up opacity-0" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
                    <div class="w-8 h-px bg-gold-400"></div>
                    <p class="text-gold-400 text-xs tracking-[0.3em] uppercase">Wedding</p>
                    <div class="w-8 h-px bg-gold-400"></div>
                </div>
                
                {{-- Couple Names --}}
                <div class="mb-8 animate-fade-in-up opacity-0" style="animation-delay: 0.4s; animation-fill-mode: forwards;">
                    <h1 class="font-display text-4xl md:text-5xl text-white font-bold">
                        {{ $invitation->bride_name }}
                    </h1>
                    <p class="text-gold-400 text-3xl my-3 font-accent">&</p>
                    <h1 class="font-display text-4xl md:text-5xl text-white font-bold">
                        {{ $invitation->groom_name }}
                    </h1>
                </div>


                {{-- Date --}}
                @if($invitation->event_date)
                <div class="flex items-center justify-center gap-4 text-white/60 animate-fade-in-up opacity-0" style="animation-delay: 0.6s; animation-fill-mode: forwards;">
                    <span class="text-2xl font-display">{{ $invitation->event_date->format('d') }}</span>
                    <div class="h-8 w-px bg-white/20"></div>
                    <span class="text-sm uppercase tracking-wider">{{ $invitation->event_date->format('M Y') }}</span>
                </div>
                @endif
                
                {{-- Guest --}}
                @if($guest)
                <div class="mt-8 pt-6 border-t border-white/10 animate-fade-in-up opacity-0" style="animation-delay: 0.8s; animation-fill-mode: forwards;">
                    <p class="text-white/40 text-xs uppercase tracking-wider mb-1">Dear</p>
                    <p class="text-white text-lg">{{ $guest->name }}</p>
                </div>
                @endif
            </div>
            
            {{-- Open Button --}}
            <button 
                x-on:click="openInvitation()"
                class="mt-8 w-full py-4 bg-gold-500 text-charcoal-900 font-bold tracking-wider uppercase text-sm rounded-2xl transition-all duration-300 hover:bg-gold-400 hover:scale-[1.02] active:scale-[0.98] shadow-gold animate-fade-in-up opacity-0"
                style="animation-delay: 1s; animation-fill-mode: forwards;"
            >
                Open Invitation
            </button>
        </div>
    </div>
    @endsection



    {{-- Main Invitation Content --}}
    <div class="bg-charcoal-900 text-white min-h-screen">
        
        {{-- Hero Section --}}
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
            {{-- Background --}}
            <div class="absolute inset-0">
                @if($invitation->cover_image_url)
                <img src="{{ $invitation->cover_image_url }}" alt="Cover" class="w-full h-full object-cover opacity-40">
                @endif
                <div class="absolute inset-0 bg-gradient-to-b from-charcoal-900/50 via-charcoal-900/80 to-charcoal-900"></div>
                <div class="absolute top-20 left-10 w-72 h-72 bg-gold-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/5 rounded-full blur-3xl"></div>
            </div>
            
            {{-- Content --}}
            <div class="relative z-10 text-center px-6 py-20">
                <div class="glass-dark rounded-3xl p-10 md:p-16 border border-white/10 max-w-2xl mx-auto">
                    <p class="text-gold-400 tracking-[0.4em] uppercase text-xs mb-8">The Wedding Of</p>
                    
                    <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-2">
                        {{ $invitation->bride_name }}
                    </h1>
                    <div class="flex items-center justify-center my-4">
                        <div class="w-12 h-px bg-gold-500"></div>
                        <span class="mx-4 text-gold-400 text-4xl font-accent">&</span>
                        <div class="w-12 h-px bg-gold-500"></div>
                    </div>
                    <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-white">
                        {{ $invitation->groom_name }}
                    </h1>
                    
                    @if($invitation->event_date)
                    <div class="mt-10 flex items-center justify-center gap-6">
                        <div class="text-center">
                            <p class="text-3xl font-display font-bold text-white">{{ $invitation->event_date->format('d') }}</p>
                            <p class="text-white/40 text-xs uppercase">Day</p>
                        </div>
                        <div class="w-px h-12 bg-white/20"></div>
                        <div class="text-center">
                            <p class="text-3xl font-display font-bold text-white">{{ $invitation->event_date->format('m') }}</p>
                            <p class="text-white/40 text-xs uppercase">Month</p>
                        </div>
                        <div class="w-px h-12 bg-white/20"></div>
                        <div class="text-center">
                            <p class="text-3xl font-display font-bold text-white">{{ $invitation->event_date->format('Y') }}</p>
                            <p class="text-white/40 text-xs uppercase">Year</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>



        {{-- Countdown Section --}}
        @if($invitation->event_date && $invitation->countdown_enabled)
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-4xl mx-auto">
                <div class="glass-dark rounded-3xl border border-white/10 p-10 md:p-12 text-center">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-xs mb-2">Counting Down</p>
                    <h2 class="font-display text-3xl text-white mb-10">Until The Big Day</h2>
                    @include('public.invitations.partials.countdown', ['theme' => 'dark', 'size' => 'large'])
                </div>
            </div>
        </section>
        @endif

        {{-- Couple Section --}}
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-xs mb-4">Meet</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">The Couple</h2>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Bride --}}
                    <div class="glass-dark rounded-3xl border border-white/10 p-8 text-center">
                        <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-br from-gold-500/20 to-gold-600/10 flex items-center justify-center border border-gold-500/30">
                            <span class="font-display text-5xl text-gold-400">{{ substr($invitation->bride_name, 0, 1) }}</span>
                        </div>
                        <h3 class="font-display text-2xl text-white mb-2">{{ $invitation->bride_name }}</h3>
                        @if($invitation->bride_parent)
                        <p class="text-white/40 text-sm">Daughter of</p>
                        <p class="text-white/60">{{ $invitation->bride_parent }}</p>
                        @endif
                    </div>
                    
                    {{-- Groom --}}
                    <div class="glass-dark rounded-3xl border border-white/10 p-8 text-center">
                        <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-br from-gold-500/20 to-gold-600/10 flex items-center justify-center border border-gold-500/30">
                            <span class="font-display text-5xl text-gold-400">{{ substr($invitation->groom_name, 0, 1) }}</span>
                        </div>
                        <h3 class="font-display text-2xl text-white mb-2">{{ $invitation->groom_name }}</h3>
                        @if($invitation->groom_parent)
                        <p class="text-white/40 text-sm">Son of</p>
                        <p class="text-white/60">{{ $invitation->groom_parent }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>



        {{-- Opening Message --}}
        @if($invitation->opening_message)
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-3xl mx-auto text-center">
                <div class="glass-dark rounded-3xl border border-white/10 p-10">
                    <svg class="w-10 h-10 mx-auto mb-6 text-gold-500/50" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="font-accent text-xl md:text-2xl text-white/80 italic leading-relaxed">
                        {{ $invitation->opening_message }}
                    </p>
                </div>
            </div>
        </section>
        @endif

        {{-- Events Section --}}
        @if($invitation->events->count() > 0)
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-xs mb-4">Save The Date</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Events</h2>
                </div>
                
                <div class="space-y-6">
                    @foreach($invitation->events as $event)
                    <div class="glass-dark rounded-3xl border border-white/10 p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center gap-6">
                            {{-- Event Icon --}}
                            <div class="w-16 h-16 rounded-2xl bg-gold-500/10 flex items-center justify-center flex-shrink-0 border border-gold-500/20">
                                <span class="text-gold-400 text-2xl">{!! $event->type_icon !!}</span>
                            </div>
                            
                            {{-- Details --}}
                            <div class="flex-1">
                                <span class="inline-block px-3 py-1 bg-gold-500/20 text-gold-400 text-xs uppercase tracking-wider rounded-full mb-2">
                                    {{ $event->type_label }}
                                </span>
                                <h3 class="font-display text-xl text-white mb-1">{{ $event->name }}</h3>
                                <p class="text-white/60">{{ $event->formatted_date }}</p>
                                @if($event->formatted_time)
                                <p class="text-white/40 text-sm">{{ $event->formatted_time }}</p>
                                @endif
                            </div>
                            
                            {{-- Venue --}}
                            <div class="md:text-right">
                                @if($event->venue_name)
                                <p class="text-white font-medium">{{ $event->venue_name }}</p>
                                @endif
                                @if($event->venue_address)
                                <p class="text-white/40 text-sm mb-3">{{ $event->venue_address }}</p>
                                @endif
                                @if($event->google_maps_url)
                                <button 
                                    x-on:click="openMaps('{{ $event->google_maps_url }}')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gold-500 text-charcoal-900 font-medium text-sm rounded-xl hover:bg-gold-400 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    Maps
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif



        {{-- Love Story --}}
        @if($invitation->story_section && count($invitation->story_section) > 0)
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-xs mb-4">Our Journey</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Love Story</h2>
                </div>
                
                <div class="space-y-6">
                    @foreach($invitation->story_section as $index => $story)
                    <div class="glass-dark rounded-3xl border border-white/10 p-6 md:p-8 flex gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl bg-gold-500/10 flex items-center justify-center border border-gold-500/20">
                                <span class="text-gold-400 font-display font-bold">{{ $index + 1 }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-gold-400 text-sm">{{ $story['date'] ?? 'Memory' }}</span>
                            <h3 class="font-display text-xl text-white mt-1 mb-2">{{ $story['title'] ?? '' }}</h3>
                            <p class="text-white/50">{{ $story['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- Gallery --}}
        @if($invitation->gallery && count($invitation->gallery) > 0)
        <section class="py-20 px-6 reveal-section">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-xs mb-4">Moments</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Gallery</h2>
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
        <section id="rsvp" class="py-20 px-6 reveal-section">
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-12">
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-xs mb-4">Respond</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">RSVP</h2>
                    <p class="text-white/50 mt-4">Let us know if you can make it</p>
                </div>
                
                @include('public.invitations.partials.rsvp-form', [
                    'invitation' => $invitation,
                    'guest' => $guest,
                    'theme' => 'dark',
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
                    <p class="text-gold-400 tracking-[0.3em] uppercase text-xs mb-4">Gift</p>
                    <h2 class="font-display text-4xl md:text-5xl text-white">Digital Gift</h2>
                    <p class="text-white/50 mt-4">Your presence is our greatest gift</p>
                </div>
                
                @include('public.invitations.partials.gift-section', [
                    'invitation' => $invitation,
                    'theme' => 'dark'
                ])
            </div>
        </section>
        @endif

        {{-- Share --}}
        <section class="py-16 px-6 reveal-section">
            <div class="max-w-2xl mx-auto text-center">
                <div class="glass-dark rounded-3xl border border-white/10 p-8">
                    <h3 class="font-display text-2xl text-white mb-6">Share Invitation</h3>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <button 
                            x-on:click="shareToWhatsApp()"
                            class="flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl transition-colors"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </button>
                        <button 
                            x-on:click="copyToClipboard(window.location.href, 'link-copy-feedback')"
                            class="flex items-center gap-2 px-6 py-3 border border-white/20 text-white hover:bg-white/10 rounded-xl transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Copy Link
                        </button>
                    </div>
                    <p id="link-copy-feedback" class="hidden mt-3 text-emerald-400 text-sm">Copied!</p>
                </div>
            </div>
        </section>



        {{-- Footer --}}
        <footer class="py-16 px-6 border-t border-white/10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="font-display text-3xl text-white mb-4">
                    {{ $invitation->bride_name }} <span class="text-gold-400">&</span> {{ $invitation->groom_name }}
                </h2>
                <p class="text-white/40 italic mb-8">Thank you for celebrating with us</p>
                <div class="mb-8">
                    <svg class="w-8 h-8 mx-auto text-gold-500 animate-heartbeat" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <p class="text-white/20 text-xs">Made with <span class="text-gold-500">Wedding Invite</span></p>
            </div>
        </footer>
    </div>
</x-layouts.public-invitation>
