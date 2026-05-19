<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Models\Invitation;
use Illuminate\Support\Facades\URL;

/**
 * SEO Service for generating meta tags, structured data, and sitemap.
 */
class SeoService
{
    private string $siteName;
    private string $siteUrl;
    private string $defaultImage;
    private string $twitterHandle;
    private string $locale;

    public function __construct()
    {
        $this->siteName = config('app.name', 'Wedding Invite');
        $this->siteUrl = config('app.url', 'https://weddinginvite.id');
        $this->defaultImage = asset('images/og-image.jpg');
        $this->twitterHandle = '@weddinginvite';
        $this->locale = 'id_ID';
    }

    /**
     * Generate homepage SEO data.
     */
    public function getHomepageSeo(): array
    {
        return [
            'title' => 'Undangan Digital Pernikahan Premium - ' . $this->siteName,
            'description' => 'Buat undangan pernikahan digital yang elegan dan premium. Fitur RSVP, QR Check-in, Amplop Digital, dan Analytics. Mulai dari Rp 0.',
            'keywords' => 'undangan digital, undangan pernikahan online, undangan digital premium, wedding invitation, undangan digital Indonesia',
            'canonical' => $this->siteUrl,
            'og' => [
                'title' => 'Buat Undangan Digital Pernikahan Premium',
                'description' => 'Platform undangan pernikahan digital terbaik di Indonesia. Desain elegan, fitur lengkap, harga terjangkau.',
                'type' => 'website',
                'url' => $this->siteUrl,
                'image' => $this->defaultImage,
                'site_name' => $this->siteName,
                'locale' => $this->locale,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'site' => $this->twitterHandle,
                'title' => 'Undangan Digital Pernikahan Premium',
                'description' => 'Platform undangan pernikahan digital terbaik di Indonesia.',
                'image' => $this->defaultImage,
            ],
            'schema' => $this->getOrganizationSchema(),
        ];
    }

    /**
     * Generate pricing page SEO data.
     */
    public function getPricingSeo(): array
    {
        return [
            'title' => 'Harga Paket Undangan Digital - ' . $this->siteName,
            'description' => 'Pilih paket undangan digital sesuai kebutuhan Anda. Mulai dari paket Basic gratis hingga Luxury dengan fitur lengkap.',
            'keywords' => 'harga undangan digital, paket undangan pernikahan, biaya undangan online',
            'canonical' => route('pricing'),
            'og' => [
                'title' => 'Harga Paket Undangan Digital',
                'description' => 'Bandingkan paket undangan digital kami. Basic, Premium, dan Luxury.',
                'type' => 'website',
                'url' => route('pricing'),
                'image' => $this->defaultImage,
                'site_name' => $this->siteName,
                'locale' => $this->locale,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'site' => $this->twitterHandle,
                'title' => 'Harga Paket Undangan Digital',
                'description' => 'Pilih paket undangan digital sesuai kebutuhan.',
                'image' => $this->defaultImage,
            ],
            'schema' => $this->getProductsSchema(),
        ];
    }

    /**
     * Generate demo page SEO data.
     */
    public function getDemoSeo(): array
    {
        return [
            'title' => 'Demo Undangan Digital - Lihat Template Premium - ' . $this->siteName,
            'description' => 'Lihat demo template undangan digital kami. Elegant Luxury, Minimal White, dan Modern Dark. Preview sebelum membuat undangan Anda.',
            'keywords' => 'demo undangan digital, contoh undangan online, template undangan pernikahan',
            'canonical' => route('demo'),
            'og' => [
                'title' => 'Demo Template Undangan Digital',
                'description' => 'Preview template undangan digital premium kami.',
                'type' => 'website',
                'url' => route('demo'),
                'image' => $this->defaultImage,
                'site_name' => $this->siteName,
                'locale' => $this->locale,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'site' => $this->twitterHandle,
                'title' => 'Demo Template Undangan Digital',
                'description' => 'Preview template undangan digital premium.',
                'image' => $this->defaultImage,
            ],
            'schema' => null,
        ];
    }

    /**
     * Generate article SEO data.
     */
    public function getArticleSeo(Article $article): array
    {
        $image = $article->thumbnail_url ?? $this->defaultImage;

        return [
            'title' => $article->seo_title . ' - ' . $this->siteName,
            'description' => $article->seo_description,
            'keywords' => is_array($article->tags) ? implode(', ', $article->tags) : '',
            'canonical' => route('articles.show', $article),
            'og' => [
                'title' => $article->seo_title,
                'description' => $article->seo_description,
                'type' => 'article',
                'url' => route('articles.show', $article),
                'image' => $image,
                'site_name' => $this->siteName,
                'locale' => $this->locale,
                'article:published_time' => $article->published_at?->toIso8601String(),
                'article:author' => $article->author?->name ?? $this->siteName,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'site' => $this->twitterHandle,
                'title' => $article->seo_title,
                'description' => $article->seo_description,
                'image' => $image,
            ],
            'schema' => $this->getArticleSchema($article),
        ];
    }

    /**
     * Generate articles list SEO data.
     */
    public function getArticlesListSeo(): array
    {
        return [
            'title' => 'Artikel & Tips Undangan Pernikahan - ' . $this->siteName,
            'description' => 'Baca artikel terbaru tentang tips pernikahan, inspirasi undangan digital, dan panduan membuat undangan yang sempurna.',
            'keywords' => 'artikel pernikahan, tips undangan digital, inspirasi wedding',
            'canonical' => route('articles.index'),
            'og' => [
                'title' => 'Artikel & Tips Undangan Pernikahan',
                'description' => 'Temukan inspirasi dan tips untuk undangan pernikahan Anda.',
                'type' => 'website',
                'url' => route('articles.index'),
                'image' => $this->defaultImage,
                'site_name' => $this->siteName,
                'locale' => $this->locale,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'site' => $this->twitterHandle,
                'title' => 'Artikel & Tips Undangan Pernikahan',
                'description' => 'Temukan inspirasi dan tips.',
                'image' => $this->defaultImage,
            ],
            'schema' => null,
        ];
    }

    /**
     * Generate invitation SEO data.
     */
    public function getInvitationSeo(Invitation $invitation): array
    {
        $title = $invitation->bride_name . ' & ' . $invitation->groom_name;
        $description = 'Undangan Pernikahan ' . $title . '. ' . ($invitation->wedding_date ? 'Tanggal ' . $invitation->wedding_date->format('d F Y') : '');
        $image = $invitation->cover_image_url ?? $this->defaultImage;

        return [
            'title' => $title . ' - Undangan Pernikahan',
            'description' => $description,
            'keywords' => 'undangan pernikahan, wedding invitation',
            'canonical' => route('invitation.public', $invitation->slug),
            'og' => [
                'title' => 'Undangan Pernikahan ' . $title,
                'description' => $description,
                'type' => 'website',
                'url' => route('invitation.public', $invitation->slug),
                'image' => $image,
                'site_name' => $this->siteName,
                'locale' => $this->locale,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'site' => $this->twitterHandle,
                'title' => 'Undangan Pernikahan ' . $title,
                'description' => $description,
                'image' => $image,
            ],
            'schema' => $this->getEventSchema($invitation),
        ];
    }

    /**
     * Generate Organization schema.
     */
    private function getOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $this->siteName,
            'url' => $this->siteUrl,
            'logo' => asset('images/logo.png'),
            'description' => 'Platform undangan pernikahan digital premium di Indonesia',
            'sameAs' => [
                'https://www.instagram.com/weddinginvite',
                'https://www.facebook.com/weddinginvite',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+62-812-3456-7890',
                'contactType' => 'customer service',
                'availableLanguage' => ['Indonesian', 'English'],
            ],
        ];
    }

    /**
     * Generate Products schema for pricing.
     */
    private function getProductsSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Harga Paket Undangan Digital',
            'description' => 'Pilih paket undangan digital sesuai kebutuhan Anda',
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->siteName,
            ],
        ];
    }

    /**
     * Generate Article schema.
     */
    private function getArticleSchema(Article $article): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->seo_title,
            'description' => $article->seo_description,
            'image' => $article->thumbnail_url ?? $this->defaultImage,
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->author?->name ?? $this->siteName,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->siteName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('articles.show', $article),
            ],
        ];
    }

    /**
     * Generate Event schema for invitation.
     */
    private function getEventSchema(Invitation $invitation): array
    {
        $events = $invitation->events ?? collect();
        $mainEvent = $events->first();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => 'Pernikahan ' . $invitation->bride_name . ' & ' . $invitation->groom_name,
            'startDate' => $invitation->wedding_date?->toIso8601String(),
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location' => $mainEvent ? [
                '@type' => 'Place',
                'name' => $mainEvent->venue_name ?? 'TBA',
                'address' => $mainEvent->venue_address ?? '',
            ] : null,
            'organizer' => [
                '@type' => 'Person',
                'name' => $invitation->bride_name . ' & ' . $invitation->groom_name,
            ],
        ];
    }

    /**
     * Generate sitemap XML content.
     */
    public function generateSitemap(): string
    {
        $urls = [];

        // Static pages
        $urls[] = ['loc' => $this->siteUrl, 'priority' => '1.0', 'changefreq' => 'daily'];
        $urls[] = ['loc' => route('pricing'), 'priority' => '0.9', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => route('demo'), 'priority' => '0.8', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => route('articles.index'), 'priority' => '0.8', 'changefreq' => 'daily'];

        // Articles
        $articles = Article::published()->get();
        foreach ($articles as $article) {
            $urls[] = [
                'loc' => route('articles.show', $article),
                'lastmod' => $article->updated_at->toW3cString(),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ];
        }

        // Published invitations
        $invitations = Invitation::where('status', 'published')->get();
        foreach ($invitations as $invitation) {
            $urls[] = [
                'loc' => route('invitation.public', $invitation->slug),
                'lastmod' => $invitation->updated_at->toW3cString(),
                'priority' => '0.6',
                'changefreq' => 'weekly',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url['loc']}</loc>\n";
            if (isset($url['lastmod'])) {
                $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            }
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate robots.txt content.
     */
    public function generateRobotsTxt(): string
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /dashboard\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /profile\n";
        $content .= "Disallow: /orders\n";
        $content .= "Disallow: /invitations/create\n";
        $content .= "Disallow: /invitations/*/edit\n";
        $content .= "\n";
        $content .= "Sitemap: {$this->siteUrl}/sitemap.xml\n";

        return $content;
    }
}
