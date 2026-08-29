<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $seo = $seo ?? [];
            $seoDescription = $seo['description'] ?? "Dr. Pushpa Patel's Dental Clinic — modern, unhurried dentistry in Bandra West, Mumbai.";
            $og = $seo['og'] ?? [];
            $twitter = $seo['twitter'] ?? [];
            $article = $seo['article'] ?? [];
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $seoDescription }}" @if($seo !== []) data-server-seo @endif>
        @if(! empty($seo['robots']))
            <meta name="robots" content="{{ $seo['robots'] }}" data-server-seo>
        @endif
        @if(! empty($seo['keywords']))
            <meta name="keywords" content="{{ $seo['keywords'] }}" data-server-seo>
        @endif
        @if(! empty($seo['canonical']))
            <link rel="canonical" href="{{ $seo['canonical'] }}" data-server-seo>
        @endif
        @if($og !== [])
            <meta property="og:site_name" content="{{ $og['site_name'] ?? config('app.name') }}" data-server-seo>
            <meta property="og:type" content="{{ $og['type'] ?? 'website' }}" data-server-seo>
            <meta property="og:title" content="{{ $og['title'] ?? $seo['title'] ?? config('app.name') }}" data-server-seo>
            <meta property="og:description" content="{{ $og['description'] ?? $seoDescription }}" data-server-seo>
            <meta property="og:url" content="{{ $og['url'] ?? $seo['canonical'] ?? url()->current() }}" data-server-seo>
            @if(! empty($og['image']))
                <meta property="og:image" content="{{ $og['image'] }}" data-server-seo>
            @endif
            @if(! empty($og['image_alt']))
                <meta property="og:image:alt" content="{{ $og['image_alt'] }}" data-server-seo>
            @endif
        @endif
        @if($twitter !== [])
            <meta name="twitter:card" content="{{ $twitter['card'] ?? 'summary_large_image' }}" data-server-seo>
            <meta name="twitter:title" content="{{ $twitter['title'] ?? $seo['title'] ?? config('app.name') }}" data-server-seo>
            <meta name="twitter:description" content="{{ $twitter['description'] ?? $seoDescription }}" data-server-seo>
            @if(! empty($twitter['image']))
                <meta name="twitter:image" content="{{ $twitter['image'] }}" data-server-seo>
            @endif
            @if(! empty($og['image_alt']))
                <meta name="twitter:image:alt" content="{{ $og['image_alt'] }}" data-server-seo>
            @endif
        @endif
        @if(! empty($article['published_time']))
            <meta property="article:published_time" content="{{ $article['published_time'] }}" data-server-seo>
        @endif
        @if(! empty($article['modified_time']))
            <meta property="article:modified_time" content="{{ $article['modified_time'] }}" data-server-seo>
        @endif

        <title inertia>{{ $seo['title'] ?? config('app.name') }}</title>

        <link rel="icon" type="image/png" href="/assets/logo.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
        @if(! empty($seo['json_ld']))
            <script type="application/ld+json" data-server-seo>{!! $seo['json_ld'] !!}</script>
        @endif
    </head>
    <body>
        @inertia
    </body>
</html>
