<!DOCTYPE html>
<html lang="{{ $currentLang->code ?? 'en' }}" dir="{{ $currentLang->direction ?? 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? 'CMS' }} | {{ \WebConf::get('site_name') ?: config('app.name', 'Laravel CMS') }}</title>
    @if(!empty($metaKeywords))
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    @if(!empty($metaDescription))
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; color: #222; background: #f8f9fa; line-height: 1.6; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        header { background: #1e293b; color: #fff; padding: 0 20px; }
        .nav-wrap { max-width: 1100px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; min-height: 60px; flex-wrap: wrap; }
        .brand { font-size: 1.25rem; font-weight: 700; color: #fff; }
        .nav-menu { list-style: none; display: flex; gap: 20px; margin: 0; padding: 0; }
        .nav-menu li { position: relative; }
        .nav-menu > li > a { color: #e2e8f0; font-weight: 500; padding: 8px 4px; display: inline-block; }
        .nav-menu > li > a:hover { color: #fff; text-decoration: none; }
        .nav-menu ul { display: none; position: absolute; top: 100%; left: 0; background: #1e293b; list-style: none; padding: 6px 0; margin: 0; min-width: 180px; border-radius: 4px; box-shadow: 0 6px 16px rgba(0,0,0,.2); z-index: 20; }
        .nav-menu li:hover > ul { display: block; }
        .nav-menu ul li a { display: block; padding: 6px 14px; color: #e2e8f0; }
        .nav-menu ul li a:hover { background: #334155; text-decoration: none; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 300px; gap: 30px; }
        @media (max-width: 820px) { .container { grid-template-columns: 1fr; } }
        .card { background: #fff; border-radius: 8px; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
        .card img.featured { width: 100%; max-height: 360px; object-fit: cover; border-radius: 6px; margin-bottom: 16px; }
        .meta { font-size: 0.875rem; color: #64748b; margin-bottom: 12px; }
        .badge { display: inline-block; background: #e2e8f0; color: #334155; padding: 2px 10px; border-radius: 999px; font-size: 0.8rem; margin-right: 6px; margin-bottom: 6px; }
        .sidebar h3 { margin-top: 0; font-size: 1rem; text-transform: uppercase; letter-spacing: .04em; color: #475569; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li { padding: 6px 0; border-bottom: 1px solid #f1f5f9; }
        footer { background: #0f172a; color: #94a3b8; padding: 24px 20px; margin-top: 40px; }
        .footer-inner { max-width: 1100px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .footer-menu { list-style: none; display: flex; gap: 16px; margin: 0; padding: 0; }
        .footer-menu a { color: #cbd5e1; }
    </style>
</head>
<body>
<header>
    <div class="nav-wrap">
        <a class="brand" href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('cms') }}">
            {{ \WebConf::get('site_name') ?: config('app.name', 'Laravel CMS') }}
        </a>
        <nav>
            <ul class="nav-menu">
                @if(!empty($headerMenu) && $headerMenu->rootItems->isNotEmpty())
                    @foreach($headerMenu->rootItems as $item)
                        <li>
                            <a href="{{ $item->resolved_url }}" target="{{ $item->target }}">{{ $item->title }}</a>
                            @if($item->children->isNotEmpty())
                                <ul>
                                    @foreach($item->children as $child)
                                        <li><a href="{{ $child->resolved_url }}" target="{{ $child->target }}">{{ $child->title }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li><a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('cms') }}">Home</a></li>
                @endif
                @if(!empty($isMultilingual) && !empty($activeLanguages))
                    <li>
                        <a href="javascript:;">{{ strtoupper($currentLang->code ?? 'en') }} &#9662;</a>
                        <ul>
                            @foreach($activeLanguages as $langOpt)
                                <li>
                                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('cms', $langOpt->code) }}">
                                        {{ $langOpt->name }} ({{ strtoupper($langOpt->code) }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    <section>
        @yield('content')
    </section>

    <aside class="sidebar">
        <div class="card">
            <h3>Categories</h3>
            @if(!empty($sidebarCategories) && $sidebarCategories->isNotEmpty())
                <ul>
                    @foreach($sidebarCategories as $cat)
                        <li><a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            @else
                <p class="meta">No categories yet.</p>
            @endif
        </div>

        <div class="card">
            <h3>Tags</h3>
            @if(!empty($sidebarTags) && $sidebarTags->isNotEmpty())
                <div>
                    @foreach($sidebarTags as $t)
                        <a class="badge" href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('tag/' . $t->slug) }}">#{{ $t->name }}</a>
                    @endforeach
                </div>
            @else
                <p class="meta">No tags yet.</p>
            @endif
        </div>
    </aside>
</main>

<footer>
    <div class="footer-inner">
        <div>&copy; {{ date('Y') }} {{ \WebConf::get('site_name') ?: config('app.name', 'Laravel CMS') }}</div>
        @if(!empty($footerMenu) && $footerMenu->rootItems->isNotEmpty())
            <ul class="footer-menu">
                @foreach($footerMenu->rootItems as $item)
                    <li><a href="{{ $item->resolved_url }}" target="{{ $item->target }}">{{ $item->title }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>
</footer>
</body>
</html>

