@extends('Theme::layout')

@php
    $langCode = $currentLang->code ?? 'en';
    $heroCfg = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSection('hero', $langCode);
    $highlightsCfg = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSection('highlights', $langCode);
    $blogCfg = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSection('latest_posts', $langCode);
    $ctaCfg = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSection('contact_cta', $langCode);

    $highlightPosts = !empty($highlightsCfg['enabled'])
        ? \ExtensionsValley\Cms\Helpers\ThemeHelper::getSectionPosts($highlightsCfg, $langCode)
        : collect();

    $blogPosts = !empty($blogCfg['enabled'])
        ? \ExtensionsValley\Cms\Helpers\ThemeHelper::getSectionPosts($blogCfg, $langCode)
        : collect();
@endphp

@section('hero')
    @if(!empty($heroCfg['enabled']))
        @php
            $bannerSlug = $heroCfg['banner_slug'] ?? '';
            $heroHtml = $bannerSlug !== ''
                ? \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render('[banner slug="' . e($bannerSlug) . '"]')
                : '';
        @endphp
        @if(!empty(trim($heroHtml)))
            <div class="lf-hero-wrap">
                {!! $heroHtml !!}
            </div>
        @else
            <section class="lf-page-banner">
                <div class="lf-container">
                    <h1>{{ $heroCfg['fallback_title'] ?: (\WebConf::get('site_name') ?: 'LaFlux CMS') }}</h1>
                    <p>{{ $heroCfg['fallback_subtitle'] ?? '' }}</p>
                </div>
            </section>
        @endif
    @endif
@endsection

@section('content')
    {{-- Customizable Highlights / Features Section (driven by Category/Tag/Sort Order) --}}
    @if(!empty($highlightsCfg['enabled']) && $highlightPosts->isNotEmpty())
        <section class="lf-section lf-section-alt">
            <div class="lf-container">
                <div class="lf-section-head">
                    @if(!empty($highlightsCfg['eyebrow']))
                        <span class="lf-eyebrow">{{ $highlightsCfg['eyebrow'] }}</span>
                    @endif
                    @if(!empty($highlightsCfg['heading']))
                        <h2>{{ $highlightsCfg['heading'] }}</h2>
                    @endif
                    @if(!empty($highlightsCfg['subtitle']))
                        <p>{{ $highlightsCfg['subtitle'] }}</p>
                    @endif
                </div>
                <div class="lf-grid-3">
                    @foreach($highlightPosts as $hPost)
                        <div class="lf-card lf-card-hover">
                            @if($hPost->featured_image)
                                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $hPost->slug) }}">
                                    <img class="lf-card-img" style="height:165px;" src="{{ str_starts_with($hPost->featured_image, 'http') ? $hPost->featured_image : asset($hPost->featured_image) }}" alt="{{ $hPost->title }}">
                                </a>
                            @endif
                            <div class="lf-card-body">
                                <h3 style="margin:0 0 8px 0; font-size:1.15rem;">
                                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $hPost->slug) }}" style="color:var(--lf-dark);">
                                        {{ $hPost->title }}
                                    </a>
                                </h3>
                                <p class="lf-meta" style="margin:0 0 12px 0; font-size:0.93rem;">
                                    {{ $hPost->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($hPost->content), 120) }}
                                </p>
                                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $hPost->slug) }}" style="font-weight:600; font-size:0.88rem;">
                                    Read more &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Customizable Latest Blog Articles Section (driven by Category/Tag/Sort Order) --}}
    @if(!empty($blogCfg['enabled']))
        <section class="lf-section">
            <div class="lf-container">
                <div class="lf-section-head">
                    @if(!empty($blogCfg['eyebrow']))
                        <span class="lf-eyebrow">{{ $blogCfg['eyebrow'] }}</span>
                    @endif
                    @if(!empty($blogCfg['heading']))
                        <h2>{{ $blogCfg['heading'] }}</h2>
                    @endif
                    @if(!empty($blogCfg['subtitle']))
                        <p>{{ $blogCfg['subtitle'] }}</p>
                    @endif
                </div>

                @if($blogPosts->isEmpty())
                    <div class="lf-card"><div class="lf-card-body"><p style="margin:0;">No published posts match this section's filter yet.</p></div></div>
                @else
                    <div class="lf-grid-3">
                        @foreach($blogPosts as $post)
                            <article class="lf-card lf-card-hover">
                                @if($post->featured_image)
                                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}">
                                        <img class="lf-card-img" src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset($post->featured_image) }}" alt="{{ $post->title }}">
                                    </a>
                                @endif
                                <div class="lf-card-body">
                                    @if($post->category)
                                        <a class="lf-pill" href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $post->category->slug) }}">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                    <h3 style="margin:8px 0 8px 0; font-size:1.15rem; line-height:1.35;">
                                        <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}" style="color:var(--lf-dark);">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <div class="lf-meta">
                                        {{ ($post->published_at ?? $post->created_at)->format('M j, Y') }}
                                        @if($post->author) &middot; {{ $post->author->name }} @endif
                                    </div>
                                    <p style="color:var(--lf-slate); font-size:0.92rem; margin:0 0 14px 0;">
                                        {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}" style="font-weight:600; font-size:0.9rem;">
                                        Read more &rarr;
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- Customizable Contact / CTA Section --}}
    @if(!empty($ctaCfg['enabled']))
        @php
            $formSlug = $ctaCfg['form_slug'] ?? '';
            $contactFormHtml = $formSlug !== ''
                ? \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render('[form slug="' . e($formSlug) . '"]')
                : '';
        @endphp
        <section class="lf-section lf-section-alt">
            <div class="lf-container">
                <div class="{{ !empty(trim($contactFormHtml)) ? 'lf-grid-2' : '' }}" style="align-items:center;">
                    <div>
                        @if(!empty($ctaCfg['eyebrow']))
                            <span class="lf-eyebrow">{{ $ctaCfg['eyebrow'] }}</span>
                        @endif
                        @if(!empty($ctaCfg['heading']))
                            <h2 style="font-size:1.95rem; margin:8px 0 12px 0;">{{ $ctaCfg['heading'] }}</h2>
                        @endif
                        @if(!empty($ctaCfg['description']))
                            <p style="color:var(--lf-muted); margin-bottom:20px;">{{ $ctaCfg['description'] }}</p>
                        @endif
                        @if(!empty($ctaCfg['button_text']) && !empty($ctaCfg['button_page_slug']))
                            <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('page/' . $ctaCfg['button_page_slug']) }}" class="lf-btn lf-btn-outline">
                                {{ $ctaCfg['button_text'] }}
                            </a>
                        @endif
                    </div>
                    @if(!empty(trim($contactFormHtml)))
                        <div>{!! $contactFormHtml !!}</div>
                    @endif
                </div>
            </div>
        </section>
    @endif
@endsection
