<x-marketing-layout :seo="$seo">
    {{-- Hero --}}
    <section class="pt-32 pb-16 bg-gradient-to-b from-charcoal-900 to-charcoal-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-gold-400 font-semibold text-sm uppercase tracking-wider mb-4">Blog & Artikel</span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-white mb-6">
                Tips & Inspirasi <span class="text-gold-400">Pernikahan</span>
            </h1>
            <p class="text-lg text-ivory-400 max-w-2xl mx-auto">
                Temukan ide, tips, dan panduan untuk membuat undangan pernikahan yang sempurna.
            </p>
        </div>
    </section>

    {{-- Featured Articles --}}
    @if($featuredArticles->count() > 0)
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-charcoal-900 mb-8">Artikel Pilihan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredArticles as $article)
                <a href="{{ route('articles.show', $article) }}" class="group">
                    <div class="bg-ivory-100 rounded-2xl aspect-video mb-4 overflow-hidden">
                        <div class="w-full h-full bg-gradient-to-br from-gold-200 to-gold-400 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-gold-600 uppercase tracking-wider">{{ $categories[$article->category] ?? $article->category }}</span>
                    <h3 class="font-display text-lg font-bold text-charcoal-900 mt-2 group-hover:text-gold-600 transition-colors">{{ $article->title }}</h3>
                    <p class="text-charcoal-600 text-sm mt-2 line-clamp-2">{{ $article->excerpt }}</p>
                    <div class="flex items-center gap-4 mt-4 text-sm text-charcoal-500">
                        <span>{{ $article->published_date }}</span>
                        <span>{{ $article->reading_time }} min read</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    {{-- All Articles --}}
    <section class="py-16 bg-ivory-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-charcoal-900 mb-8">Semua Artikel</h2>

            @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $article)
                <a href="{{ route('articles.show', $article) }}" class="group bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-soft-lg transition-all border border-ivory-200">
                    <div class="aspect-video bg-gradient-to-br from-ivory-200 to-ivory-300 flex items-center justify-center">
                        <svg class="w-10 h-10 text-ivory-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-gold-600 uppercase tracking-wider">{{ $categories[$article->category] ?? $article->category }}</span>
                        <h3 class="font-display text-lg font-bold text-charcoal-900 mt-2 group-hover:text-gold-600 transition-colors">{{ $article->title }}</h3>
                        <p class="text-charcoal-600 text-sm mt-2 line-clamp-2">{{ $article->excerpt }}</p>
                        <div class="flex items-center gap-4 mt-4 text-sm text-charcoal-500">
                            <span>{{ $article->published_date }}</span>
                            <span>{{ $article->reading_time }} min read</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $articles->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-ivory-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <h3 class="font-display text-xl font-bold text-charcoal-900 mb-2">Belum Ada Artikel</h3>
                <p class="text-charcoal-600">Artikel akan segera hadir. Stay tuned!</p>
            </div>
            @endif
        </div>
    </section>
</x-marketing-layout>
