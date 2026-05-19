<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => '10 Tips Membuat Undangan Digital yang Elegan',
                'slug' => '10-tips-membuat-undangan-digital-elegan',
                'excerpt' => 'Pelajari cara membuat undangan pernikahan digital yang memukau tamu undangan Anda dengan desain yang elegan dan profesional.',
                'content' => $this->getArticleContent1(),
                'category' => 'tips',
                'tags' => ['undangan digital', 'tips pernikahan', 'desain'],
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Panduan Lengkap RSVP Digital untuk Pernikahan',
                'slug' => 'panduan-lengkap-rsvp-digital-pernikahan',
                'excerpt' => 'Ketahui semua yang perlu Anda tahu tentang sistem RSVP digital untuk memudahkan pengelolaan tamu undangan.',
                'content' => $this->getArticleContent2(),
                'category' => 'tutorial',
                'tags' => ['rsvp', 'tutorial', 'manajemen tamu'],
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Tren Undangan Pernikahan 2025: Minimalis & Modern',
                'slug' => 'tren-undangan-pernikahan-2025-minimalis-modern',
                'excerpt' => 'Temukan tren undangan pernikahan terbaru tahun 2025 dengan gaya minimalis dan modern yang sedang populer.',
                'content' => $this->getArticleContent3(),
                'category' => 'inspirasi',
                'tags' => ['tren', 'inspirasi', 'minimalis', '2025'],
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
        ];

        foreach ($articles as $articleData) {
            Article::create($articleData);
        }

        $this->command->info('Articles seeded successfully!');
    }

    private function getArticleContent1(): string
    {
        return <<<HTML
<h2>Mengapa Undangan Digital?</h2>
<p>Di era digital seperti sekarang, undangan pernikahan digital menjadi pilihan yang semakin populer. Selain ramah lingkungan, undangan digital juga lebih praktis dan mudah disebarkan.</p>

<h3>1. Pilih Template yang Sesuai</h3>
<p>Pastikan template yang Anda pilih mencerminkan kepribadian Anda sebagai pasangan. Template yang elegan dan timeless biasanya menjadi pilihan favorit.</p>

<h3>2. Gunakan Foto Berkualitas Tinggi</h3>
<p>Foto yang berkualitas akan membuat undangan Anda terlihat lebih profesional dan memukau.</p>

<h3>3. Perhatikan Tipografi</h3>
<p>Pilih font yang mudah dibaca namun tetap elegan. Kombinasikan font serif untuk judul dan sans-serif untuk body text.</p>

<h3>4. Jaga Konsistensi Warna</h3>
<p>Gunakan palet warna yang konsisten dengan tema pernikahan Anda.</p>

<h3>5. Sertakan Informasi Lengkap</h3>
<p>Pastikan semua informasi penting seperti tanggal, waktu, lokasi, dan dress code tercantum dengan jelas.</p>

<h3>6. Aktifkan Fitur RSVP</h3>
<p>Fitur RSVP digital memudahkan Anda mengelola daftar tamu yang akan hadir.</p>

<h3>7. Tambahkan Peta Lokasi</h3>
<p>Sertakan peta interaktif agar tamu dapat dengan mudah menemukan lokasi acara.</p>

<h3>8. Uji di Berbagai Perangkat</h3>
<p>Pastikan undangan Anda tampil dengan baik di smartphone, tablet, dan desktop.</p>

<h3>9. Sebarkan di Waktu yang Tepat</h3>
<p>Kirimkan undangan 6-8 minggu sebelum hari H untuk memberi waktu tamu mempersiapkan diri.</p>

<h3>10. Follow Up dengan Reminder</h3>
<p>Kirimkan pengingat 1-2 minggu sebelum acara untuk memastikan kehadiran tamu.</p>
HTML;
    }

    private function getArticleContent2(): string
    {
        return <<<HTML
<h2>Apa itu RSVP Digital?</h2>
<p>RSVP digital adalah sistem konfirmasi kehadiran online yang memudahkan pengelolaan tamu undangan pernikahan Anda.</p>

<h3>Keuntungan RSVP Digital</h3>
<ul>
<li>Mudah diakses oleh tamu kapan saja</li>
<li>Data tersimpan otomatis dan terorganisir</li>
<li>Hemat waktu dan tenaga</li>
<li>Ramah lingkungan</li>
</ul>

<h3>Cara Setup RSVP Digital</h3>
<p>Berikut langkah-langkah mudah untuk mengatur sistem RSVP digital pada undangan Anda:</p>

<h4>1. Aktifkan Fitur RSVP</h4>
<p>Masuk ke dashboard undangan dan aktifkan fitur RSVP pada menu pengaturan.</p>

<h4>2. Tentukan Batas Waktu</h4>
<p>Atur deadline RSVP minimal 2 minggu sebelum hari H.</p>

<h4>3. Customize Form RSVP</h4>
<p>Sesuaikan form RSVP dengan kebutuhan Anda, seperti menambahkan pertanyaan tentang preferensi makanan atau jumlah tamu yang akan diajak.</p>

<h4>4. Monitor Response</h4>
<p>Pantau respon tamu secara real-time melalui dashboard analytics.</p>
HTML;
    }

    private function getArticleContent3(): string
    {
        return <<<HTML
<h2>Tren Undangan Pernikahan 2025</h2>
<p>Tahun 2025 membawa angin segar dalam dunia undangan pernikahan. Berikut adalah beberapa tren yang sedang populer:</p>

<h3>1. Desain Minimalis</h3>
<p>Less is more. Desain minimalis dengan ruang putih yang cukup dan tipografi yang bersih menjadi favorit tahun ini.</p>

<h3>2. Palet Warna Earth Tone</h3>
<p>Warna-warna natural seperti sage green, terracotta, dan dusty rose mendominasi tren tahun ini.</p>

<h3>3. Animasi Subtle</h3>
<p>Animasi yang halus dan tidak berlebihan memberikan sentuhan modern tanpa mengganggu pengalaman membaca.</p>

<h3>4. Integrasi Multimedia</h3>
<p>Video pre-wedding dan playlist musik menjadi fitur yang semakin diminati.</p>

<h3>5. Fitur Interaktif</h3>
<p>Countdown timer, guest book digital, dan gallery foto interaktif membuat undangan lebih engaging.</p>

<h3>6. Mobile-First Design</h3>
<p>Mengingat sebagian besar tamu akan membuka undangan melalui smartphone, desain yang mobile-friendly menjadi prioritas utama.</p>
HTML;
    }
}
