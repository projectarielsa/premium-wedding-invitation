<x-marketing-layout :seo="$seo">
    {{-- Hero --}}
    <section class="pt-32 pb-16 bg-gradient-to-b from-charcoal-900 to-charcoal-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-gold-400 font-semibold text-sm uppercase tracking-wider mb-4">Demo Template</span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-white mb-6">
                Preview Template <span class="text-gold-400">Premium</span>
            </h1>
            <p class="text-lg text-ivory-400 max-w-2xl mx-auto">
                Lihat koleksi template undangan digital kami. Klik preview untuk melihat tampilan lengkap.
            </p>
        </div>
    </section>

    {{-- Templates Grid --}}
    <section class="py-20 bg-ivory-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($demoTemplates as $template)
                <div id="{{ $template['slug'] }}" class="group bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-soft-xl transition-all duration-500 border border-ivory-200">
                    {{-- Preview Area --}}
                    <div class="relative aspect-[9/16] max-h-[500px] overflow-hidden bg-gradient-to-br from-ivory-100 to-ivory-200">

                        {{-- Demo Invitation Preview --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center" style="background: linear-gradient(135deg, {{ $template['colors'][2] ?? '#FAF8F5' }} 0%, {{ $template['colors'][0] ?? '#D4AF37' }}22 100%);">
                            <div class="w-20 h-20 mb-6 rounded-full flex items-center justify-center" style="background: {{ $template['colors'][0] ?? '#D4AF37' }};">
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </div>
                            <p class="text-sm uppercase tracking-widest mb-2" style="color: {{ $template['colors'][1] ?? '#1A1A1A' }};">The Wedding Of</p>
                            <h3 class="font-display text-3xl font-bold mb-2" style="color: {{ $template['colors'][1] ?? '#1A1A1A' }};">Sarah & Budi</h3>
                            <p class="text-sm" style="color: {{ $template['colors'][1] ?? '#1A1A1A' }}80;">20 Desember 2025</p>
                            <div class="mt-8 w-16 h-0.5" style="background: {{ $template['colors'][0] ?? '#D4AF37' }};"></div>
                        </div>

                        {{-- Premium Badge --}}
                        @if($template['is_premium'])
                        <div class="absolute top-4 right-4 z-10">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gold-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Premium
                            </span>
                        </div>
                        @else
                        <div class="absolute top-4 right-4 z-10">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                Gratis
                            </span>
                        </div>
                        @endif
                    </div>


                    {{-- Template Info --}}
                    <div class="p-6">
                        <h3 class="font-display text-xl font-bold text-charcoal-900 mb-2">{{ $template['name'] }}</h3>
                        <p class="text-charcoal-600 text-sm mb-4">{{ $template['description'] }}</p>

                        {{-- Features --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($template['features'] as $feature)
                            <span class="inline-block px-2 py-1 bg-ivory-100 text-charcoal-600 text-xs rounded-md">{{ $feature }}</span>
                            @endforeach
                        </div>

                        {{-- Color Palette --}}
                        <div class="flex items-center gap-4 mb-6">
                            <span class="text-sm text-charcoal-500">Warna:</span>
                            <div class="flex items-center gap-2">
                                @foreach($template['colors'] as $color)
                                <div class="w-6 h-6 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $color }}" title="{{ $color }}"></div>
                                @endforeach
                            </div>
                        </div>

                        {{-- CTA --}}
                        <div class="flex gap-3">
                            <a href="{{ route('register') }}" class="btn btn-primary flex-1 justify-center">
                                Gunakan Template
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-br from-charcoal-900 to-charcoal-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-3xl md:text-4xl font-bold text-white mb-6">
                Suka dengan Template Kami?
            </h2>
            <p class="text-lg text-ivory-400 mb-8">
                Daftar sekarang dan buat undangan digital impianmu dalam hitungan menit.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    Mulai Gratis Sekarang
                </a>
                <a href="{{ route('pricing') }}" class="btn btn-lg bg-white/10 text-white border border-white/20 hover:bg-white/20">
                    Lihat Harga
                </a>
            </div>
        </div>
    </section>
</x-marketing-layout>
