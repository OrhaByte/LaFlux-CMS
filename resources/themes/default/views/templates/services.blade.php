@extends('Theme::layout')

@php
    $langCode = $currentLang->code ?? 'en';
    $tpl = $templateData ?? [];
    $servicePosts = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSectionPosts([
        'category_id' => $tpl['category_id'] ?? '',
        'tag_id' => $tpl['tag_id'] ?? '',
        'sort_order' => $tpl['sort_order'] ?? 'oldest',
        'limit' => $tpl['limit'] ?? 6,
    ], $langCode);
@endphp

@section('hero')
    <section class="lf-page-banner" style="padding:72px 0;">
        <div class="lf-container">
            @if(!empty($tpl['eyebrow']))
                <span class="lf-pill" style="background:rgba(255,255,255,0.16); color:#fff;">{{ $tpl['eyebrow'] }}</span>
            @endif
            <h1 style="margin-top:10px; font-size:2.5rem;">{{ $page->title }}</h1>
            @if(!empty($tpl['subtitle']))
                <p style="margin-top:10px; font-size:1.1rem;">{{ $tpl['subtitle'] }}</p>
            @endif
        </div>
    </section>
@endsection

@section('content')
    @if(!empty(trim(strip_tags((string) $page->content))))
        <section class="lf-section lf-section-alt" style="padding:44px 0;">
            <div class="lf-container">
                <div style="max-width:860px; margin:0 auto; font-size:1.05rem; color:var(--lf-slate);">
                    @shortcode($page->content)
                </div>
            </div>
        </section>
    @endif

    {{-- Fancy Services Showcase Grid (Alternating Featured + Cards) --}}
    <section class="lf-section">
        <div class="lf-container">
            @if($servicePosts->isEmpty())
                <div class="lf-card"><div class="lf-card-body"><p style="margin:0;">Select a Services Category in this page's Template Options to display services here.</p></div></div>
            @else
                <div class="lf-grid-3">
                    @foreach($servicePosts as $idx => $srv)
                        <div class="lf-card lf-card-hover" style="display:flex; flex-direction:column;">
                            @if($srv->featured_image)
                                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $srv->slug) }}">
                                    <img class="lf-card-img" style="height:190px;" src="{{ str_starts_with($srv->featured_image, 'http') ? $srv->featured_image : asset($srv->featured_image) }}" alt="{{ $srv->title }}">
                                </a>
                            @endif
                            <div class="lf-card-body" style="flex:1; display:flex; flex-direction:column;">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                                    <span class="lf-pill">0{{ $idx + 1 }}</span>
                                    @if($srv->category)
                                        <small class="lf-meta" style="margin:0;">{{ $srv->category->name }}</small>
                                    @endif
                                </div>
                                <h3 style="margin:0 0 10px 0; font-size:1.2rem;">
                                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $srv->slug) }}" style="color:var(--lf-dark);">
                                        {{ $srv->title }}
                                    </a>
                                </h3>
                                <p style="color:var(--lf-muted); font-size:0.93rem; flex:1; margin:0 0 16px 0;">
                                    {{ $srv->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($srv->content), 130) }}
                                </p>
                                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $srv->slug) }}" class="lf-btn lf-btn-outline" style="text-align:center;">
                                    Explore Details &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Bottom CTA & Form --}}
    @if(!empty($tpl['cta_heading']) || !empty($tpl['form_slug']))
        @php
            $formHtml = !empty($tpl['form_slug'])
                ? \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render('[form slug="' . e($tpl['form_slug']) . '"]')
                : '';
        @endphp
        <section class="lf-section lf-section-alt">
            <div class="lf-container">
                <div class="{{ !empty(trim($formHtml)) ? 'lf-grid-2' : '' }}" style="align-items:center;">
                    <div>
                        <span class="lf-eyebrow">Start a Conversation</span>
                        <h2 style="font-size:1.95rem; margin:8px 0 12px 0;">{{ $tpl['cta_heading'] ?? 'Ready to Build Something Great?' }}</h2>
                        @if(!empty($tpl['cta_text']))
                            <p style="color:var(--lf-muted);">{{ $tpl['cta_text'] }}</p>
                        @endif
                    </div>
                    @if(!empty(trim($formHtml)))
                        <div>{!! $formHtml !!}</div>
                    @endif
                </div>
            </div>
        </section>
    @endif
@endsection

