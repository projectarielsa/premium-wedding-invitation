{{-- Premium Marketing Navbar --}}
<nav 
    x-data="{ 
        isOpen: false, 
        isScrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.isScrolled = window.scrollY > 20;
            });
        }
    }"
    :class="{ 'bg-white/95 backdrop-blur-lg shadow-soft': isScrolled, 'bg-transparent': !isScrolled }"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-gradient-to-br from-gold-400 to-gold-600 rounded-xl flex items-center justify-center shadow-gold transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <span class="font-display text-xl font-bold" :class="{ 'text-charcoal-900': isScrolled, 'text-white': !isScrolled }">
                    Wedding<span class="text-gold-500">Invite</span>
                </span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-8">
                <a href="#features" class="nav-link-marketing" :class="{ 'text-charcoal-700': isScrolled, 'text-white/90': !isScrolled }">
                    Fitur
                </a>
                <a href="#templates" class="nav-link-marketing" :class="{ 'text-charcoal-700': isScrolled, 'text-white/90': !isScrolled }">
                    Template
                </a>
                <a href="{{ route('pricing') }}" class="nav-link-marketing" :class="{ 'text-charcoal-700': isScrolled, 'text-white/90': !isScrolled }">
                    Harga
                </a>
                <a href="{{ route('demo') }}" class="nav-link-marketing" :class="{ 'text-charcoal-700': isScrolled, 'text-white/90': !isScrolled }">
                    Demo
                </a>
                <a href="{{ route('articles.index') }}" class="nav-link-marketing" :class="{ 'text-charcoal-700': isScrolled, 'text-white/90': !isScrolled }">
                    Artikel
                </a>
            </div>

            {{-- Desktop CTA --}}
            <div class="hidden lg:flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="font-medium transition-colors" :class="{ 'text-charcoal-700 hover:text-gold-600': isScrolled, 'text-white hover:text-gold-300': !isScrolled }">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        Mulai Gratis
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <button 
                @click="isOpen = !isOpen" 
                class="lg:hidden p-2 rounded-lg transition-colors"
                :class="{ 'text-charcoal-700 hover:bg-charcoal-100': isScrolled, 'text-white hover:bg-white/10': !isScrolled }"
                aria-label="Toggle menu"
            >
                <svg x-show="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div 
            x-show="isOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="lg:hidden absolute top-full left-0 right-0 bg-white border-t border-ivory-200 shadow-soft-lg"
            x-cloak
        >
            <div class="px-4 py-6 space-y-4">
                <a href="#features" @click="isOpen = false" class="block py-2 text-charcoal-700 hover:text-gold-600 transition-colors">
                    Fitur
                </a>
                <a href="#templates" @click="isOpen = false" class="block py-2 text-charcoal-700 hover:text-gold-600 transition-colors">
                    Template
                </a>
                <a href="{{ route('pricing') }}" class="block py-2 text-charcoal-700 hover:text-gold-600 transition-colors">
                    Harga
                </a>
                <a href="{{ route('demo') }}" class="block py-2 text-charcoal-700 hover:text-gold-600 transition-colors">
                    Demo
                </a>
                <a href="{{ route('articles.index') }}" class="block py-2 text-charcoal-700 hover:text-gold-600 transition-colors">
                    Artikel
                </a>

                <div class="pt-4 border-t border-ivory-200 space-y-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary w-full justify-center">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline w-full justify-center">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary w-full justify-center">
                            Mulai Gratis
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-link-marketing {
        @apply font-medium text-sm transition-colors hover:text-gold-500;
    }
</style>
