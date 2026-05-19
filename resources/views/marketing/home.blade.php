<x-marketing-layout :seo="$seo">
    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-charcoal-900 via-charcoal-800 to-charcoal-900">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="hero-hearts" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                        <path d="M40 50l-2-1.8C30 41 25 36.5 25 31c0-4.4 3.4-8 7.5-8 2.4 0 4.6 1.1 6.5 2.9 1.9-1.8 4.1-2.9 6.5-2.9 4.1 0 7.5 3.6 7.5 8 0 5.5-5 10-13 17.2L40 50z" fill="currentColor" class="text-gold-500"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#hero-hearts)"/>
            </svg>
        </div>
        
        {{-- Gradient Orbs --}}
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold-500/20 rounded-full filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-rose-500/10 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>


        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 md:py-40">
            <div class="text-center max-w-4xl mx-auto">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mb-8 animate-fade-in">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-sm text-ivory-300">Dipercaya 1000+ Pasangan di Indonesia</span>
                </div>

                {{-- Main Headline --}}
                <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6 animate-fade-in-up">
                    Buat Undangan Digital
                    <span class="block mt-2 text-gradient-gold">Pernikahan Premium</span>
                    <span class="block mt-2">yang Elegan</span>
                </h1>

                {{-- Subheadline --}}
                <p class="text-lg md:text-xl text-ivory-400 mb-10 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
                    Desain mewah, fitur lengkap, dan mudah digunakan. Buat undangan impian Anda dalam hitungan menit.
                </p>


                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-xl w-full sm:w-auto group">
                        <span>Mulai Sekarang</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('demo') }}" class="btn btn-xl w-full sm:w-auto bg-white/10 backdrop-blur-sm text-white border border-white/20 hover:bg-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Lihat Demo</span>
                    </a>
                    <a href="#pricing" class="btn btn-xl w-full sm:w-auto text-ivory-300 hover:text-white">
                        Lihat Harga
                    </a>
                </div>

                {{-- Trust Badges --}}
                <div class="flex flex-wrap items-center justify-center gap-6 text-ivory-500 text-sm animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Setup 10 Menit</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Gratis Paket Basic</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Support 24/7</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-ivory-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>


    {{-- Social Proof / Stats Section --}}
    <section class="py-16 bg-white border-b border-ivory-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = {{ $stats['invitations_created'] }}; let step = target / 50; let interval = setInterval(() => { count += step; if (count >= target) { count = target; clearInterval(interval); } }, 30); }, 500)">
                    <div class="font-display text-4xl md:text-5xl font-bold text-gold-600 mb-2">
                        <span x-text="Math.floor(count).toLocaleString()">0</span>+
                    </div>
                    <p class="text-charcoal-600">Undangan Dibuat</p>
                </div>
                <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = {{ $stats['happy_customers'] }}; let step = target / 50; let interval = setInterval(() => { count += step; if (count >= target) { count = target; clearInterval(interval); } }, 30); }, 700)">
                    <div class="font-display text-4xl md:text-5xl font-bold text-gold-600 mb-2">
                        <span x-text="Math.floor(count).toLocaleString()">0</span>+
                    </div>
                    <p class="text-charcoal-600">Pasangan Bahagia</p>
                </div>
                <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = {{ $stats['rsvp_sent'] }}; let step = target / 50; let interval = setInterval(() => { count += step; if (count >= target) { count = target; clearInterval(interval); } }, 30); }, 900)">
                    <div class="font-display text-4xl md:text-5xl font-bold text-gold-600 mb-2">
                        <span x-text="Math.floor(count).toLocaleString()">0</span>+
                    </div>
                    <p class="text-charcoal-600">RSVP Terkirim</p>
                </div>
                <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = {{ $stats['total_visitors'] }}; let step = target / 50; let interval = setInterval(() => { count += step; if (count >= target) { count = target; clearInterval(interval); } }, 30); }, 1100)">
                    <div class="font-display text-4xl md:text-5xl font-bold text-gold-600 mb-2">
                        <span x-text="Math.floor(count).toLocaleString()">0</span>+
                    </div>
                    <p class="text-charcoal-600">Total Pengunjung</p>
                </div>
            </div>
        </div>
    </section>


    {{-- Features Section --}}
    <section id="features" class="py-20 md:py-32 bg-ivory-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block text-gold-600 font-semibold text-sm uppercase tracking-wider mb-4">Fitur Unggulan</span>
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-charcoal-900 mb-6">
                    Semua yang Anda Butuhkan untuk <span class="text-gold-600">Hari Spesial</span>
                </h2>
                <p class="text-lg text-charcoal-600">
                    Platform undangan digital terlengkap dengan fitur premium untuk membuat momen pernikahan Anda lebih berkesan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-soft hover:shadow-soft-lg transition-all duration-300 hover:-translate-y-1 border border-ivory-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-gold-400 to-gold-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">Desain Premium</h3>
                    <p class="text-charcoal-600">Template eksklusif dengan desain elegan yang bisa dikustomisasi sesuai tema pernikahan Anda.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-soft hover:shadow-soft-lg transition-all duration-300 hover:-translate-y-1 border border-ivory-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">RSVP Management</h3>
                    <p class="text-charcoal-600">Kelola konfirmasi kehadiran tamu dengan mudah. Lihat siapa yang hadir secara real-time.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-soft hover:shadow-soft-lg transition-all duration-300 hover:-translate-y-1 border border-ivory-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">WhatsApp Invite</h3>
                    <p class="text-charcoal-600">Kirim undangan langsung via WhatsApp dengan link personal untuk setiap tamu.</p>
                </div>


                {{-- Feature 4 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-soft hover:shadow-soft-lg transition-all duration-300 hover:-translate-y-1 border border-ivory-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">QR Code Check-in</h3>
                    <p class="text-charcoal-600">Sistem check-in modern dengan QR code untuk memudahkan registrasi tamu di hari H.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-soft hover:shadow-soft-lg transition-all duration-300 hover:-translate-y-1 border border-ivory-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">Amplop Digital</h3>
                    <p class="text-charcoal-600">Terima hadiah atau angpao secara digital dengan fitur amplop digital yang aman.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="group bg-white rounded-2xl p-8 shadow-soft hover:shadow-soft-lg transition-all duration-300 hover:-translate-y-1 border border-ivory-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">Analytics Dashboard</h3>
                    <p class="text-charcoal-600">Pantau statistik undangan Anda. Lihat berapa banyak yang membuka dan merespon.</p>
                </div>
            </div>
        </div>
    </section>


    {{-- Templates Section --}}
    <section id="templates" class="py-20 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block text-gold-600 font-semibold text-sm uppercase tracking-wider mb-4">Template Premium</span>
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-charcoal-900 mb-6">
                    Pilih Template <span class="text-gold-600">Favoritmu</span>
                </h2>
                <p class="text-lg text-charcoal-600">
                    Koleksi template eksklusif yang dirancang dengan perhatian detail untuk momen spesial Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($demoTemplates as $template)
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-soft-xl transition-all duration-500 border border-ivory-200">
                    {{-- Template Preview --}}
                    <div class="relative aspect-[3/4] overflow-hidden bg-gradient-to-br from-ivory-100 to-ivory-200">
                        {{-- Placeholder visual --}}
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center p-6">
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-gold-300 to-gold-500 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </div>
                                <p class="font-display text-2xl font-bold text-charcoal-800">{{ $template['name'] }}</p>
                            </div>
                        </div>
                        
                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-charcoal-900/80 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                            <a href="{{ route('demo') }}#{{ $template['slug'] }}" class="btn btn-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Preview
                            </a>
                        </div>

                        {{-- Premium Badge --}}
                        @if($template['is_premium'])
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gold-500 text-white text-xs font-semibold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Premium
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Template Info --}}
                    <div class="p-6">
                        <h3 class="font-display text-xl font-bold text-charcoal-900 mb-2">{{ $template['name'] }}</h3>
                        <p class="text-charcoal-600 text-sm mb-4">{{ $template['description'] }}</p>
                        
                        {{-- Color Palette --}}
                        <div class="flex items-center gap-2">
                            @foreach($template['colors'] as $color)
                            <div class="w-6 h-6 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $color }}"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('demo') }}" class="btn btn-outline btn-lg">
                    Lihat Semua Template
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>


    {{-- Pricing Section --}}
    <section id="pricing" class="py-20 md:py-32 bg-gradient-to-b from-ivory-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block text-gold-600 font-semibold text-sm uppercase tracking-wider mb-4">Harga Transparan</span>
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-charcoal-900 mb-6">
                    Pilih Paket <span class="text-gold-600">Sesuai Kebutuhan</span>
                </h2>
                <p class="text-lg text-charcoal-600">
                    Harga terjangkau dengan fitur premium. Tidak ada biaya tersembunyi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @foreach($packages as $package)
                <div class="relative bg-white rounded-2xl shadow-soft hover:shadow-soft-xl transition-all duration-300 overflow-hidden border {{ $package->is_featured ? 'border-gold-400 ring-2 ring-gold-400/20' : 'border-ivory-200' }}">
                    {{-- Featured Badge --}}
                    @if($package->is_featured)
                    <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-gold-400 to-gold-600 text-white text-center py-2 text-sm font-semibold">
                        {{ $package->badge ?? 'Paling Populer' }}
                    </div>
                    @endif

                    <div class="p-8 {{ $package->is_featured ? 'pt-14' : '' }}">
                        {{-- Package Name --}}
                        <h3 class="font-display text-2xl font-bold text-charcoal-900 mb-2">{{ $package->name }}</h3>
                        <p class="text-charcoal-500 text-sm mb-6">{{ $package->description }}</p>

                        {{-- Price --}}
                        <div class="mb-6">
                            @if($package->has_discount)
                            <span class="text-charcoal-400 line-through text-lg">{{ $package->formatted_original_price }}</span>
                            @endif
                            <div class="flex items-baseline gap-1">
                                <span class="font-display text-4xl font-bold text-charcoal-900">{{ $package->formatted_price }}</span>
                                <span class="text-charcoal-500">/ {{ $package->duration_label }}</span>
                            </div>
                        </div>

                        {{-- Features --}}
                        <ul class="space-y-3 mb-8">
                            @foreach($package->features_list ?? [] as $feature)
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-charcoal-600 text-sm">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>

                        {{-- CTA --}}
                        @auth
                        <a href="{{ route('orders.checkout', $package) }}" class="btn w-full justify-center {{ $package->is_featured ? 'btn-primary' : 'btn-outline' }}">
                            Pilih {{ $package->name }}
                        </a>
                        @else
                        <a href="{{ route('register') }}?package={{ $package->slug }}" class="btn w-full justify-center {{ $package->is_featured ? 'btn-primary' : 'btn-outline' }}">
                            Mulai Sekarang
                        </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('pricing') }}" class="text-gold-600 hover:text-gold-700 font-medium inline-flex items-center gap-2">
                    Bandingkan semua fitur
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>


    {{-- How It Works Section --}}
    <section class="py-20 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block text-gold-600 font-semibold text-sm uppercase tracking-wider mb-4">Cara Kerja</span>
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-charcoal-900 mb-6">
                    4 Langkah <span class="text-gold-600">Mudah</span>
                </h2>
                <p class="text-lg text-charcoal-600">
                    Buat undangan digital dalam hitungan menit dengan proses yang simpel.
                </p>
            </div>

            <div class="relative">
                {{-- Timeline Line --}}
                <div class="hidden lg:block absolute top-1/2 left-0 right-0 h-0.5 bg-gradient-to-r from-gold-200 via-gold-400 to-gold-200 -translate-y-1/2"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    {{-- Step 1 --}}
                    <div class="relative text-center">
                        <div class="relative z-10 w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center shadow-gold">
                            <span class="font-display text-2xl font-bold text-white">1</span>
                        </div>
                        <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">Pilih Paket</h3>
                        <p class="text-charcoal-600">Pilih paket yang sesuai dengan kebutuhan dan budget Anda.</p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="relative text-center">
                        <div class="relative z-10 w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center shadow-gold">
                            <span class="font-display text-2xl font-bold text-white">2</span>
                        </div>
                        <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">Isi Data</h3>
                        <p class="text-charcoal-600">Lengkapi informasi pernikahan, upload foto, dan pilih template.</p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="relative text-center">
                        <div class="relative z-10 w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center shadow-gold">
                            <span class="font-display text-2xl font-bold text-white">3</span>
                        </div>
                        <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">Sebarkan</h3>
                        <p class="text-charcoal-600">Share undangan via WhatsApp, sosmed, atau QR code.</p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="relative text-center">
                        <div class="relative z-10 w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center shadow-gold">
                            <span class="font-display text-2xl font-bold text-white">4</span>
                        </div>
                        <h3 class="font-display text-xl font-bold text-charcoal-900 mb-3">Pantau RSVP</h3>
                        <p class="text-charcoal-600">Monitor konfirmasi kehadiran tamu secara real-time.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- Testimonials Section --}}
    <section class="py-20 md:py-32 bg-ivory-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block text-gold-600 font-semibold text-sm uppercase tracking-wider mb-4">Testimoni</span>
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-charcoal-900 mb-6">
                    Kata Mereka tentang <span class="text-gold-600">Kami</span>
                </h2>
                <p class="text-lg text-charcoal-600">
                    Ribuan pasangan telah mempercayakan undangan pernikahan mereka kepada kami.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-2xl p-6 shadow-soft border border-ivory-200 hover:shadow-soft-lg transition-shadow">
                    {{-- Stars --}}
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < $testimonial['rating']; $i++)
                        <svg class="w-5 h-5 text-gold-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>

                    {{-- Quote --}}
                    <p class="text-charcoal-600 mb-6 italic">"{{ $testimonial['review'] }}"</p>

                    {{-- Author --}}
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold-300 to-gold-500 flex items-center justify-center">
                            <span class="text-white font-semibold">{{ $testimonial['avatar_initials'] }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-charcoal-900">{{ $testimonial['couple_name'] }}</p>
                            <p class="text-sm text-charcoal-500">{{ $testimonial['wedding_date'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- FAQ Section --}}
    <section id="faq" class="py-20 md:py-32 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block text-gold-600 font-semibold text-sm uppercase tracking-wider mb-4">FAQ</span>
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-charcoal-900 mb-6">
                    Pertanyaan <span class="text-gold-600">Umum</span>
                </h2>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                @foreach($faqs as $index => $faq)
                <div class="border border-ivory-200 rounded-xl overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                        class="w-full flex items-center justify-between p-6 text-left bg-white hover:bg-ivory-50 transition-colors"
                    >
                        <span class="font-semibold text-charcoal-900 pr-4">{{ $faq['question'] }}</span>
                        <svg 
                            class="w-5 h-5 text-gold-500 flex-shrink-0 transition-transform duration-200"
                            :class="{ 'rotate-180': openFaq === {{ $index }} }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div 
                        x-show="openFaq === {{ $index }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="px-6 pb-6"
                        x-cloak
                    >
                        <p class="text-charcoal-600">{{ $faq['answer'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <p class="text-charcoal-600 mb-4">Masih punya pertanyaan?</p>
                <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya punya pertanyaan tentang undangan digital.') }}" 
                   target="_blank"
                   class="btn btn-primary">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/>
                    </svg>
                    Chat via WhatsApp
                </a>
            </div>
        </div>
    </section>
</x-marketing-layout>
