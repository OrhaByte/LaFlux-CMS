@extends('Theme::layout')

@php
    $langCode = $currentLang->code ?? 'en';
    $tpl = $templateData ?? [];
    $heroHtml = !empty($tpl['banner_slug'])
        ? \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render('[banner slug="' . e($tpl['banner_slug']) . '"]')
        : '';
    $featuredPosts = !empty($tpl['category_id'])
        ? \ExtensionsValley\Cms\Helpers\ThemeHelper::getSectionPosts([
            'category_id' => $tpl['category_id'],
            'sort_order' => $tpl['sort_order'] ?? 'latest',
            'limit' => $tpl['limit'] ?? 3,
        ], $langCode)
        : collect();
@endphp

@section('hero')
    @if(!empty(trim($heroHtml)))
        <div class="lf-hero-wrap">{!! $heroHtml !!}</div>
    @else
        <section class="lf-page-banner">
            <div class="lf-container">
                <h1>{{ $page->title }}</h1>
            </div>
        </section>
    @endif
@endsection

@section('content')
    <section class="lf-section lf-section-alt">
        <div class="lf-container">
            @shortcode($page->content)
        </div>
    </section>

    @if($featuredPosts->isNotEmpty())
        <section class="lf-section">
            <div class="lf-container">
                <div class="lf-grid-3">
                    @foreach($featuredPosts as $post)
                        <article class="lf-card lf-card-hover">
                            @if($post->featured_image)
                                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}">
                                    <img class="lf-card-img" src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset($post->featured_image) }}" alt="{{ $post->title }}">
                                </a>
                            @endif
                            <div class="lf-card-body">
                                <h3 style="margin:0 0 8px 0; font-size:1.15rem;">
                                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}" style="color:var(--lf-dark);">{{ $post->title }}</a>
                                </h3>
                                <p class="lf-meta" style="margin:0 0 12px 0;">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}" style="font-weight:600;">Read more &rarr;</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

