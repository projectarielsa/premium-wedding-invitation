<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>{{ $invitation->seo_title_display }}</title>
    <meta name="description" content="{{ $invitation->seo_description_display }}">
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $invitation->seo_title_display }}">
    <meta property="og:description" content="{{ $invitation->seo_description_display }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($invitation->seo_image || $invitation->cover_image)
        <meta property="og:image" content="{{ $invitation->seo_image ? asset('storage/' . $invitation->seo_image) : $invitation->cover_image_url }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $invitation->seo_title_display }}">
    <meta name="twitter:description" content="{{ $invitation->seo_description_display }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23D4AF37'><path d='M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z'/></svg>">

    {{-- Preconnect for Performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Additional Public Invitation Styles --}}
    <style>
        [x-cloak] { display: none !important; }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar for invitation pages */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.5);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 175, 55, 0.8);
        }

        /* Section reveal animations */
        .reveal-section {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        
        .reveal-section.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Floating animation for decorative elements */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Sparkle animation */
        @keyframes sparkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }
        
        .animate-sparkle {
            animation: sparkle 2s ease-in-out infinite;
        }

        /* Pulse glow for buttons */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
            50% { box-shadow: 0 0 20px 10px rgba(212, 175, 55, 0); }
        }
        
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        /* Fade in up animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }

        /* Stagger delay classes */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        .delay-600 { animation-delay: 600ms; }
        .delay-700 { animation-delay: 700ms; }
        .delay-800 { animation-delay: 800ms; }

        /* Text shadow for readability on images */
        .text-shadow {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .text-shadow-lg {
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        /* Gradient text */
        .text-gradient-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #F5D16E 50%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glass effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .glass-dark {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Decorative line */
        .decorative-line {
            width: 60px;
            height: 1px;
            background: linear-gradient(90deg, transparent, currentColor, transparent);
        }

        /* Cover open animation */
        @keyframes coverSlideUp {
            from {
                transform: translateY(0);
                opacity: 1;
            }
            to {
                transform: translateY(-100%);
                opacity: 0;
            }
        }
        
        .cover-slide-up {
            animation: coverSlideUp 1s ease-in-out forwards;
        }

        /* Heartbeat animation */
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            14% { transform: scale(1.1); }
            28% { transform: scale(1); }
            42% { transform: scale(1.1); }
            70% { transform: scale(1); }
        }
        
        .animate-heartbeat {
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        /* Custom template styles will be injected here */
        @stack('template-styles')
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    {{-- Main Invitation Content --}}
    <div 
        x-data="invitationApp({
            slug: '{{ $invitation->slug }}',
            guestToken: '{{ $guest?->slug_token ?? '' }}',
            guestName: '{{ $guest?->name ?? '' }}',
            eventDate: '{{ $invitation->event_date?->toIso8601String() ?? '' }}',
            musicUrl: '{{ $invitation->music_url ?? '' }}',
            musicAutoplay: {{ $invitation->music_autoplay ? 'true' : 'false' }},
            rsvpEnabled: {{ $invitation->rsvp_enabled ? 'true' : 'false' }},
            giftEnabled: {{ $invitation->gift_enabled ? 'true' : 'false' }},
            isPreview: {{ $isPreview ? 'true' : 'false' }}
        })"
        x-init="init()"
        class="min-h-screen"
    >
        {{-- Opening Cover --}}
        <div 
            x-show="showCover" 
            x-transition:leave="cover-slide-up"
            class="fixed inset-0 z-50 flex items-center justify-center"
            :class="templateClass + '-cover'"
        >
            @yield('cover')
        </div>

        {{-- Main Content (hidden until cover is opened) --}}
        <div x-show="!showCover" x-cloak>
            {{ $slot }}
        </div>

        {{-- Floating Music Toggle --}}
        @if($invitation->music_url)
            <button 
                x-on:click="toggleMusic()"
                class="fixed bottom-24 right-4 z-40 w-12 h-12 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110"
                :class="isPlaying ? 'bg-gold-500 text-white' : 'bg-white text-gold-600 border border-gold-300'"
                title="Toggle Music"
            >
                <svg x-show="isPlaying" class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
                <svg x-show="!isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                </svg>
            </button>
        @endif

        {{-- Floating RSVP Button --}}
        @if($invitation->rsvp_enabled)
            <button 
                x-on:click="scrollToSection('rsvp')"
                x-show="!showCover && showFloatingRsvp"
                x-transition
                class="fixed bottom-4 right-4 z-40 px-6 py-3 bg-gold-500 text-white font-medium rounded-full shadow-lg flex items-center gap-2 transition-all duration-300 hover:bg-gold-600 hover:scale-105 animate-pulse-glow"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>RSVP</span>
            </button>
        @endif

        {{-- Hidden Audio Element --}}
        @if($invitation->music_url)
            <audio 
                x-ref="audioPlayer" 
                :src="musicUrl" 
                loop 
                preload="auto"
            ></audio>
        @endif
    </div>

    {{-- Alpine.js Invitation App --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('invitationApp', (config) => ({
                // Configuration
                slug: config.slug,
                guestToken: config.guestToken,
                guestName: config.guestName,
                eventDate: config.eventDate,
                musicUrl: config.musicUrl,
                musicAutoplay: config.musicAutoplay,
                rsvpEnabled: config.rsvpEnabled,
                giftEnabled: config.giftEnabled,
                isPreview: config.isPreview,
                
                // State
                showCover: true,
                isPlaying: false,
                showFloatingRsvp: false,
                templateClass: '',
                countdown: {
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0
                },
                
                // RSVP Form State
                rsvpForm: {
                    attendance_status: '',
                    attendance_count: 1,
                    message: '',
                    guest_token: config.guestToken,
                    guest_name: config.guestName || ''
                },
                rsvpSubmitting: false,
                rsvpSuccess: false,
                rsvpError: '',
                
                init() {
                    // Start countdown if event date exists
                    if (this.eventDate) {
                        this.updateCountdown();
                        setInterval(() => this.updateCountdown(), 1000);
                    }
                    
                    // Setup intersection observer for animations
                    this.setupScrollAnimations();
                    
                    // Show floating RSVP after scrolling
                    window.addEventListener('scroll', () => {
                        this.showFloatingRsvp = window.scrollY > 500;
                    });

                    // Track page view (only if not preview)
                    if (!this.isPreview) {
                        this.trackEvent('page_view', {
                            is_unique: !this.hasVisitedBefore(),
                            is_guest: !!this.guestToken
                        });
                        this.markAsVisited();
                    }
                },
                
                openInvitation() {
                    this.showCover = false;
                    
                    // Auto-play music if enabled
                    if (this.musicAutoplay && this.musicUrl) {
                        this.$nextTick(() => {
                            this.playMusic();
                        });
                    }
                },
                
                toggleMusic() {
                    if (this.isPlaying) {
                        this.pauseMusic();
                    } else {
                        this.playMusic();
                    }
                },
                
                playMusic() {
                    const audio = this.$refs.audioPlayer;
                    if (audio) {
                        audio.play().then(() => {
                            this.isPlaying = true;
                        }).catch(e => {
                            console.log('Audio autoplay prevented:', e);
                        });
                    }
                },
                
                pauseMusic() {
                    const audio = this.$refs.audioPlayer;
                    if (audio) {
                        audio.pause();
                        this.isPlaying = false;
                    }
                },
                
                updateCountdown() {
                    if (!this.eventDate) return;
                    
                    const now = new Date().getTime();
                    const eventTime = new Date(this.eventDate).getTime();
                    const diff = eventTime - now;
                    
                    if (diff > 0) {
                        this.countdown.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        this.countdown.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.countdown.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        this.countdown.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    } else {
                        this.countdown = { days: 0, hours: 0, minutes: 0, seconds: 0 };
                    }
                },
                
                scrollToSection(sectionId) {
                    const element = document.getElementById(sectionId);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                },
                
                setupScrollAnimations() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('revealed');
                            }
                        });
                    }, {
                        threshold: 0.1,
                        rootMargin: '0px 0px -50px 0px'
                    });
                    
                    document.querySelectorAll('.reveal-section').forEach(el => {
                        observer.observe(el);
                    });
                },
                
                async copyToClipboard(text, feedbackId = null) {
                    try {
                        await navigator.clipboard.writeText(text);
                        
                        if (feedbackId) {
                            const el = document.getElementById(feedbackId);
                            if (el) {
                                el.classList.remove('hidden');
                                setTimeout(() => el.classList.add('hidden'), 2000);
                            }
                        }
                        
                        // Track copy event
                        this.trackEvent('gift_copy');
                        
                        return true;
                    } catch (err) {
                        console.error('Failed to copy:', err);
                        return false;
                    }
                },
                
                shareToWhatsApp() {
                    const url = this.guestToken 
                        ? `${window.location.origin}/invite/${this.slug}/${this.guestToken}`
                        : `${window.location.origin}/invite/${this.slug}`;
                    
                    const text = encodeURIComponent(`Kami mengundang Anda untuk hadir di acara pernikahan kami. Klik untuk melihat undangan: ${url}`);
                    window.open(`https://wa.me/?text=${text}`, '_blank');
                    
                    this.trackEvent('whatsapp_share');
                },
                
                async submitRsvp() {
                    if (this.rsvpSubmitting) return;
                    
                    this.rsvpSubmitting = true;
                    this.rsvpError = '';
                    
                    try {
                        const response = await fetch(`/invite/${this.slug}/rsvp`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.rsvpForm)
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok) {
                            this.rsvpSuccess = true;
                            this.trackEvent('rsvp_submit');
                        } else {
                            this.rsvpError = data.error || data.message || 'Failed to submit RSVP. Please try again.';
                        }
                    } catch (err) {
                        this.rsvpError = 'An error occurred. Please try again.';
                        console.error('RSVP submission error:', err);
                    } finally {
                        this.rsvpSubmitting = false;
                    }
                },
                
                trackEvent(event, extraData = {}) {
                    if (this.isPreview) return;
                    
                    fetch(`/analytics/${this.slug}/track`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            event: event,
                            device: this.getDeviceType(),
                            referrer: document.referrer || 'direct',
                            ...extraData
                        })
                    }).catch(err => console.log('Analytics error:', err));
                },
                
                trackGiftView() {
                    this.trackEvent('gift_view');
                },
                
                openMaps(url) {
                    window.open(url, '_blank');
                    this.trackEvent('map_click');
                },
                
                getDeviceType() {
                    const ua = navigator.userAgent;
                    if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
                        return 'tablet';
                    }
                    if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
                        return 'mobile';
                    }
                    return 'desktop';
                },
                
                hasVisitedBefore() {
                    return localStorage.getItem(`visited_${this.slug}`) === 'true';
                },
                
                markAsVisited() {
                    localStorage.setItem(`visited_${this.slug}`, 'true');
                }
            }));
        });
    </script>

    @stack('scripts')
</body>
</html>
