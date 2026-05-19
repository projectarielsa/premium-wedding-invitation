<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Package;
use App\Models\Template;
use App\Models\User;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function __construct(
        private SeoService $seoService
    ) {}

    /**
     * Display the marketing landing page.
     */
    public function index(): View
    {
        // Get packages for pricing section
        $packages = Package::publicDisplay()->get();
        $featuredPackage = $packages->firstWhere('is_featured', true);

        // Get statistics for social proof
        $stats = $this->getMarketingStats();

        // Demo templates data
        $demoTemplates = $this->getDemoTemplates();

        // Testimonials
        $testimonials = $this->getTestimonials();

        // FAQ data
        $faqs = $this->getFaqs();

        // SEO data
        $seo = $this->seoService->getHomepageSeo();

        return view('marketing.home', compact(
            'packages',
            'featuredPackage',
            'stats',
            'demoTemplates',
            'testimonials',
            'faqs',
            'seo'
        ));
    }

    /**
     * Display the demo showcase page.
     */
    public function demo(): View
    {
        $demoTemplates = $this->getDemoTemplates();
        $seo = $this->seoService->getDemoSeo();

        return view('marketing.demo', compact('demoTemplates', 'seo'));
    }

    /**
     * Generate robots.txt
     */
    public function robots(): Response
    {
        $content = $this->seoService->generateRobotsTxt();

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Generate sitemap.xml
     */
    public function sitemap(): Response
    {
        $content = $this->seoService->generateSitemap();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Track WhatsApp CTA click.
     */
    public function trackWhatsappClick(Request $request): \Illuminate\Http\JsonResponse
    {
        // In production, you might want to log this to analytics
        // For now, we'll just return success
        return response()->json(['success' => true]);
    }

    /**
     * Get marketing statistics.
     */
    private function getMarketingStats(): array
    {
        $invitationsCount = Invitation::count();
        $usersCount = User::count();

        // Simulate realistic stats for demo
        return [
            'invitations_created' => max(1250, $invitationsCount + 1200),
            'happy_customers' => max(850, $usersCount + 800),
            'rsvp_sent' => max(45000, $invitationsCount * 35),
            'total_visitors' => max(125000, $invitationsCount * 100),
        ];
    }

    /**
     * Get demo templates data.
     */
    private function getDemoTemplates(): array
    {
        return [
            [
                'id' => 'elegant-luxury',
                'name' => 'Elegant Luxury',
                'slug' => 'elegant-luxury',
                'description' => 'Template mewah dengan sentuhan emas dan desain klasik yang elegan.',
                'preview_url' => route('demo') . '#elegant-luxury',
                'thumbnail' => '/images/templates/elegant-luxury.jpg',
                'category' => 'premium',
                'is_premium' => true,
                'features' => ['Animasi Premium', 'Gallery Fullscreen', 'Music Background'],
                'colors' => ['#D4AF37', '#1A1A1A', '#FAF8F5'],
            ],
            [
                'id' => 'minimal-white',
                'name' => 'Minimal White',
                'slug' => 'minimal-white',
                'description' => 'Desain minimalis dengan sentuhan putih bersih dan modern.',
                'preview_url' => route('demo') . '#minimal-white',
                'thumbnail' => '/images/templates/minimal-white.jpg',
                'category' => 'standard',
                'is_premium' => false,
                'features' => ['Clean Design', 'Fast Loading', 'Mobile Optimized'],
                'colors' => ['#FFFFFF', '#333333', '#F5F5F5'],
            ],
            [
                'id' => 'modern-dark',
                'name' => 'Modern Dark',
                'slug' => 'modern-dark',
                'description' => 'Tema gelap modern dengan aksen warna yang dramatis.',
                'preview_url' => route('demo') . '#modern-dark',
                'thumbnail' => '/images/templates/modern-dark.jpg',
                'category' => 'premium',
                'is_premium' => true,
                'features' => ['Dark Theme', 'Neon Accents', 'Video Support'],
                'colors' => ['#0A0A0A', '#F43F5E', '#FFFFFF'],
            ],
        ];
    }

    /**
     * Get testimonials data.
     */
    private function getTestimonials(): array
    {
        return [
            [
                'id' => 1,
                'couple_name' => 'Sarah & Budi',
                'wedding_date' => 'Menikah Juni 2025',
                'review' => 'Undangan digitalnya sangat elegan dan mudah digunakan. Tamu-tamu kami sangat terkesan dengan desainnya!',
                'avatar_initials' => 'SB',
                'rating' => 5,
            ],
            [
                'id' => 2,
                'couple_name' => 'Rina & Andi',
                'wedding_date' => 'Menikah Mei 2025',
                'review' => 'Fitur RSVP-nya sangat membantu kami mengelola daftar tamu. Recommended banget!',
                'avatar_initials' => 'RA',
                'rating' => 5,
            ],
            [
                'id' => 3,
                'couple_name' => 'Maya & Dimas',
                'wedding_date' => 'Menikah April 2025',
                'review' => 'Harganya terjangkau tapi kualitasnya premium. Terima kasih Wedding Invite!',
                'avatar_initials' => 'MD',
                'rating' => 5,
            ],
            [
                'id' => 4,
                'couple_name' => 'Lisa & Reza',
                'wedding_date' => 'Menikah Maret 2025',
                'review' => 'Proses pembuatannya cepat dan customer servicenya responsif. Top!',
                'avatar_initials' => 'LR',
                'rating' => 5,
            ],
        ];
    }

    /**
     * Get FAQ data.
     */
    private function getFaqs(): array
    {
        return [
            [
                'question' => 'Bagaimana cara membuat undangan digital?',
                'answer' => 'Sangat mudah! Daftar akun, pilih template, isi data pernikahan Anda, dan sebarkan link undangan. Prosesnya hanya membutuhkan waktu 10-15 menit.',
            ],
            [
                'question' => 'Apakah bisa edit undangan setelah dibuat?',
                'answer' => 'Ya, Anda bisa mengedit undangan kapan saja melalui dashboard. Perubahan akan langsung terlihat pada link undangan yang sudah disebarkan.',
            ],
            [
                'question' => 'Bagaimana sistem pembayarannya?',
                'answer' => 'Kami menerima pembayaran via transfer bank dan e-wallet. Setelah konfirmasi pembayaran, paket akan langsung aktif.',
            ],
            [
                'question' => 'Berapa lama undangan bisa diakses?',
                'answer' => 'Undangan dapat diakses sesuai durasi paket yang Anda pilih. Paket Basic berlaku 3 bulan, Premium 6 bulan, dan Luxury 1 tahun.',
            ],
            [
                'question' => 'Apakah bisa custom domain?',
                'answer' => 'Ya, fitur custom domain tersedia pada paket Luxury. Anda bisa menggunakan domain seperti sarah-budi.wedding.',
            ],
            [
                'question' => 'Apakah ada revisi untuk undangan?',
                'answer' => 'Anda bisa melakukan revisi sendiri melalui dashboard tanpa batas. Tim kami juga siap membantu via WhatsApp untuk paket Premium dan Luxury.',
            ],
        ];
    }
}
