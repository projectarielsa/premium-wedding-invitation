<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Wedding Invite') }} - Premium Wedding Invitation Platform</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23D4AF37'><path d='M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z'/></svg>">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-body antialiased bg-ivory-100">
        <div class="min-h-full flex">
            {{-- Left Side - Decorative Panel (Hidden on mobile) --}}
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-charcoal-900 via-charcoal-800 to-charcoal-900 overflow-hidden">
                {{-- Background Pattern --}}
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="hearts" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                                <path d="M30 35l-1.5-1.4C23.4 28.4 20 25.3 20 21.5c0-3.1 2.4-5.5 5.5-5.5 1.7 0 3.4.8 4.5 2.1 1.1-1.3 2.8-2.1 4.5-2.1 3.1 0 5.5 2.4 5.5 5.5 0 3.8-3.4 6.9-8.5 12.1L30 35z" fill="currentColor" class="text-gold-500"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#hearts)"/>
                    </svg>
                </div>
                
                {{-- Content --}}
                <div class="relative z-10 flex flex-col justify-between w-full p-12">
                    {{-- Logo --}}
                    <div>
                        <x-application-logo variant="light" />
                    </div>
                    
                    {{-- Main Content --}}
                    <div class="max-w-md">
                        <h1 class="font-display text-4xl lg:text-5xl font-bold text-white leading-tight mb-6">
                            Create Beautiful Wedding Invitations
                        </h1>
                        <p class="text-lg text-ivory-400 leading-relaxed mb-8">
                            Design stunning digital invitations, manage your guest list, and track RSVPs all in one elegant platform.
                        </p>
                        
                        {{-- Features --}}
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gold-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-ivory-300">Premium invitation templates</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gold-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-ivory-300">Easy guest management & RSVP tracking</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gold-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-ivory-300">WhatsApp & social sharing</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Testimonial --}}
                    <div class="mt-auto pt-12">
                        <blockquote class="border-l-2 border-gold-500 pl-6">
                            <p class="text-ivory-300 italic font-accent text-lg mb-4">
                                "The most elegant way to invite our wedding guests. The platform made everything so simple and beautiful."
                            </p>
                            <footer class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gold-500/20 flex items-center justify-center">
                                    <span class="text-gold-400 font-semibold">SR</span>
                                </div>
                                <div>
                                    <p class="text-white font-medium">Sarah & Ryan</p>
                                    <p class="text-ivory-500 text-sm">Married June 2025</p>
                                </div>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            {{-- Right Side - Form --}}
            <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-8 xl:px-12">
                <div class="w-full max-w-md mx-auto">
                    {{-- Mobile Logo --}}
                    <div class="lg:hidden mb-8 text-center">
                        <a href="/" class="inline-block">
                            <x-application-logo />
                        </a>
                    </div>

                    {{-- Form Card --}}
                    <div class="bg-white rounded-2xl shadow-soft-lg border border-ivory-200 p-8">
                        {{ $slot }}
                    </div>

                    {{-- Footer --}}
                    <div class="mt-8 text-center">
                        <p class="text-sm text-charcoal-500">
                            &copy; {{ date('Y') }} Wedding Invite. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
