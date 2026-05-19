{{-- Premium Marketing Footer --}}
<footer class="bg-charcoal-900 text-white">
    {{-- CTA Section --}}
    <div class="border-b border-charcoal-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
                    Siap Membuat Undangan Digital 
                    <span class="text-gradient-gold">Impianmu?</span>
                </h2>
                <p class="text-lg text-ivory-400 mb-10">
                    Bergabung dengan ribuan pasangan yang telah mempercayakan momen spesial mereka kepada kami.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Coba Gratis Sekarang
                    </a>
                    <a href="{{ route('demo') }}" class="btn btn-lg w-full sm:w-auto bg-charcoal-800 text-white hover:bg-charcoal-700 border border-charcoal-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lihat Demo
                    </a>
                    <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya tertarik membuat undangan digital premium.') }}" 
                       target="_blank" 
                       class="btn btn-lg w-full sm:w-auto bg-emerald-500 text-white hover:bg-emerald-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Hubungi Admin
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Footer --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 lg:gap-12">
            {{-- Brand Column --}}
            <div class="col-span-2 md:col-span-4 lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-gold-400 to-gold-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                    <span class="font-display text-xl font-bold">
                        Wedding<span class="text-gold-500">Invite</span>
                    </span>
                </a>
                <p class="text-ivory-400 mb-6 max-w-sm">
                    Platform undangan pernikahan digital premium dengan desain elegan dan fitur lengkap untuk hari spesial Anda.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-charcoal-800 flex items-center justify-center hover:bg-gold-500 transition-colors" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-charcoal-800 flex items-center justify-center hover:bg-gold-500 transition-colors" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-charcoal-800 flex items-center justify-center hover:bg-gold-500 transition-colors" aria-label="TikTok">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Product Links --}}
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Produk</h4>
                <ul class="space-y-3">
                    <li><a href="#features" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Fitur</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Harga</a></li>
                    <li><a href="{{ route('demo') }}" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Demo</a></li>
                    <li><a href="#templates" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Template</a></li>
                </ul>
            </div>

            {{-- Company Links --}}
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Perusahaan</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Tentang Kami</a></li>
                    <li><a href="{{ route('articles.index') }}" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Blog</a></li>
                    <li><a href="#" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Karir</a></li>
                    <li><a href="#" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Partner</a></li>
                </ul>
            </div>

            {{-- Support Links --}}
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Bantuan</h4>
                <ul class="space-y-3">
                    <li><a href="#faq" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">FAQ</a></li>
                    <li><a href="#" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">Kebijakan Privasi</a></li>
                    <li>
                        <a href="https://wa.me/6281234567890" target="_blank" class="text-ivory-400 hover:text-gold-400 transition-colors text-sm">
                            Hubungi Kami
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-charcoal-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-ivory-500 text-sm">
                    &copy; {{ date('Y') }} Wedding Invite. All rights reserved.
                </p>
                <div class="flex items-center gap-6 text-sm text-ivory-500">
                    <span>Made with <span class="text-red-500">❤</span> in Indonesia</span>
                </div>
            </div>
        </div>
    </div>
</footer>
