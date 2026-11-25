<!DOCTYPE html>
<html lang="{{ $currentLang->code ?? 'en' }}" dir="{{ $currentLang->direction ?? 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? 'Home' }} | {{ \WebConf::get('site_name') ?: 'LaFlux CMS' }}</title>
    @if(!empty($metaKeywords))
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    @if(!empty($metaDescription))
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    @if(\WebConf::get('fav_icon') != "")
        <link rel="shortcut icon" href="{{ asset(\WebConf::get('fav_icon')) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('themes/default/theme.css') }}">
    @stack('styles')
</head>
<body>
    @include('Theme::partials.header')

    @yield('hero')

    <main>
        @yield('content')
    </main>

    @include('Theme::partials.footer')

    @stack('scripts')
</body>
</html>

