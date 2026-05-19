<x-marketing-layout :seo="$seo">
    <article>
        {{-- Hero --}}
        <header class="pt-32 pb-16 bg-gradient-to-b from-charcoal-900 to-charcoal-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-gold-400 hover:text-gold-300 mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Artikel
                </a>
                <span class="inline-block text-gold-400 font-semibold text-sm uppercase tracking-wider mb-4">{{ \App\Models\Article::getCategories()[$article->category] ?? $article->category }}</span>
                <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                    {{ $article->title }}
                </h1>
                <div class="flex items-center justify-center gap-6 text-ivory-400 text-sm">
                    <span>{{ $article->published_date }}</span>
                    <span>{{ $article->reading_time }} min read</span>
                    <span>{{ number_format($article->view_count) }} views</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <div class="py-16 bg-white">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg max-w-none prose-headings:font-display prose-headings:font-bold prose-a:text-gold-600 hover:prose-a:text-gold-700">
                    {!! $article->content !!}
                </div>

                {{-- Tags --}}
                @if($article->tags && count($article->tags) > 0)
                <div class="mt-12 pt-8 border-t border-ivory-200">
                    <span class="text-sm text-charcoal-500 mr-2">Tags:</span>
                    @foreach($article->tags as $tag)
                    <span class="inline-block px-3 py-1 bg-ivory-100 text-charcoal-600 text-sm rounded-full mr-2 mb-2">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Share --}}
                <div class="mt-8 pt-8 border-t border-ivory-200">
                    <span class="text-sm text-charcoal-500 mr-4">Share:</span>
                    <div class="inline-flex items-center gap-3">
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('articles.show', $article)) }}" target="_blank" class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('articles.show', $article)) }}" target="_blank" class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article)) }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </article>


    {{-- Related Articles --}}
    @if($relatedArticles->count() > 0)
    <section class="py-16 bg-ivory-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-charcoal-900 mb-8">Artikel Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedArticles as $related)
                <a href="{{ route('articles.show', $related) }}" class="group bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-soft-lg transition-all border border-ivory-200">
                    <div class="aspect-video bg-gradient-to-br from-ivory-200 to-ivory-300 flex items-center justify-center">
                        <svg class="w-10 h-10 text-ivory-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="font-display text-lg font-bold text-charcoal-900 group-hover:text-gold-600 transition-colors">{{ $related->title }}</h3>
                        <p class="text-charcoal-600 text-sm mt-2 line-clamp-2">{{ $related->excerpt }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-marketing-layout>
