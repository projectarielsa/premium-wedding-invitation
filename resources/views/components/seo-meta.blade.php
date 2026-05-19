@props(['seo' => []])

@php
    $title = $seo['title'] ?? config('app.name') . ' - Undangan Digital Premium';
    $description = $seo['description'] ?? 'Platform undangan pernikahan digital premium di Indonesia';
    $keywords = $seo['keywords'] ?? 'undangan digital, undangan pernikahan, wedding invitation';
    $canonical = $seo['canonical'] ?? url()->current();
    $og = $seo['og'] ?? [];
    $twitter = $seo['twitter'] ?? [];
    $schema = $seo['schema'] ?? null;
@endphp

{{-- Primary Meta Tags --}}
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $og['type'] ?? 'website' }}">
<meta property="og:url" content="{{ $og['url'] ?? url()->current() }}">
<meta property="og:title" content="{{ $og['title'] ?? $title }}">
<meta property="og:description" content="{{ $og['description'] ?? $description }}">
<meta property="og:image" content="{{ $og['image'] ?? asset('images/og-image.jpg') }}">
<meta property="og:site_name" content="{{ $og['site_name'] ?? config('app.name') }}">
<meta property="og:locale" content="{{ $og['locale'] ?? 'id_ID' }}">

@if(isset($og['article:published_time']))
<meta property="article:published_time" content="{{ $og['article:published_time'] }}">
@endif
@if(isset($og['article:author']))
<meta property="article:author" content="{{ $og['article:author'] }}">
@endif

{{-- Twitter --}}
<meta property="twitter:card" content="{{ $twitter['card'] ?? 'summary_large_image' }}">
<meta property="twitter:url" content="{{ $twitter['url'] ?? url()->current() }}">
<meta property="twitter:title" content="{{ $twitter['title'] ?? $title }}">
<meta property="twitter:description" content="{{ $twitter['description'] ?? $description }}">
<meta property="twitter:image" content="{{ $twitter['image'] ?? asset('images/og-image.jpg') }}">
@if(isset($twitter['site']))
<meta property="twitter:site" content="{{ $twitter['site'] }}">
@endif

{{-- Structured Data / JSON-LD --}}
@if($schema)
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
