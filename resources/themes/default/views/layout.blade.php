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
    @php
        $brandingCfg = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSection('branding', $currentLang->code ?? null);
    @endphp
    @if(!empty($brandingCfg['primary_color']))
        <style>:root { --lf-primary: {{ $brandingCfg['primary_color'] }}; }</style>
    @endif
    @php
        $gaId = trim((string) \WebConf::get('ga_measurement_id'));
        $gtmId = trim((string) \WebConf::get('gtm_container_id'));
        $customHead = (string) \WebConf::get('custom_head_scripts');
        $customBody = (string) \WebConf::get('custom_body_scripts');
    @endphp
    @if($gaId !== '')
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ urlencode($gaId) }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @json($gaId));
        </script>
    @endif
    @if($gtmId !== '')
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer',@json($gtmId));</script>
    @endif
    @if(trim($customHead) !== '')
        {!! $customHead !!}
    @endif
    @stack('styles')
</head>
<body>
    @if($gtmId !== '')
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ urlencode($gtmId) }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    @if(trim($customBody) !== '')
        {!! $customBody !!}
    @endif
    @include('Theme::partials.header')

    @yield('hero')

    <main>
        @yield('content')
    </main>

    @include('Theme::partials.footer')
    @include('Theme::partials.cookie-banner')

    @stack('scripts')
</body>
</html>

