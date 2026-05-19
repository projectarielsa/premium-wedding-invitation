<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta --}}
    <x-seo-meta :seo="$seo ?? []" />

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23D4AF37'><path d='M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z'/></svg>">

    {{-- Fonts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Extra Head Content --}}
    @stack('head')
</head>
<body class="font-body antialiased bg-white text-charcoal-800">
    {{-- Skip to main content for accessibility --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-gold-500 text-white px-4 py-2 rounded-lg z-50">
        Skip to main content
    </a>

    {{-- Navigation --}}
    <x-marketing.navbar />

    {{-- Main Content --}}
    <main id="main-content">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-marketing.footer />

    {{-- WhatsApp Floating Button --}}
    <x-marketing.whatsapp-button />

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>
