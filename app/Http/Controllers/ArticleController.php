<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\SeoService;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(
        private SeoService $seoService
    ) {}

    /**
     * Display listing of articles.
     */
    public function index(): View
    {
        $featuredArticles = Article::published()
            ->featured()
            ->latest()
            ->take(3)
            ->get();

        $articles = Article::published()
            ->latest()
            ->paginate(9);

        $categories = Article::getCategories();

        $seo = $this->seoService->getArticlesListSeo();

        return view('articles.index', compact(
            'featuredArticles',
            'articles',
            'categories',
            'seo'
        ));
    }

    /**
     * Display a single article.
     */
    public function show(Article $article): View
    {
        // Only show published articles
        if (!$article->is_published) {
            abort(404);
        }

        // Increment view count
        $article->incrementViews();

        // Related articles
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest()
            ->take(3)
            ->get();

        $seo = $this->seoService->getArticleSeo($article);

        return view('articles.show', compact('article', 'relatedArticles', 'seo'));
    }
}
