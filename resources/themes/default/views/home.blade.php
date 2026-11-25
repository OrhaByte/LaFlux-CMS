@extends('Theme::layout')

@section('hero')
    @php
        $heroHtml = \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render('[banner slug="home-hero"]');
    @endphp
    @if(!empty(trim($heroHtml)))
        <div class="lf-hero-wrap">
            {!! $heroHtml !!}
        </div>
    @else
        <section class="lf-page-banner">
            <div class="lf-container">
                <h1>{{ \WebConf::get('site_name') ?: 'LaFlux CMS' }}</h1>
                <p>Build fast, modern, multilingual websites with modular Laravel 13 packages, dynamic menus, sliders, and forms.</p>
            </div>
        </section>
    @endif
@endsection

@section('content')
    {{-- Multi-Purpose Highlights Section --}}
    <section class="lf-section lf-section-alt">
        <div class="lf-container">
            <div class="lf-section-head">
                <span class="lf-eyebrow">Why LaFlux CMS</span>
                <h2>Everything You Need to Launch Fast</h2>
                <p>A flexible, modular content platform engineered for performance, multilingual publishing, and effortless administration.</p>
            </div>
            <div class="lf-grid-3">
                <div class="lf-card lf-card-hover">
                    <div class="lf-card-body">
                        <div class="lf-feature-icon">&#9889;</div>
                        <h3 style="margin:0 0 8px 0;">Modular Architecture</h3>
                        <p class="lf-meta" style="margin:0; font-size:0.93rem;">Clean Laravel 13 packages for Posts, Pages, Media, Menus, Sliders, and Forms with role-based permissions.</p>
                    </div>
                </div>
                <div class="lf-card lf-card-hover">
                    <div class="lf-card-body">
                        <div class="lf-feature-icon">&#127760;</div>
                        <h3 style="margin:0 0 8px 0;">Multilingual &amp; RTL Ready</h3>
                        <p class="lf-meta" style="margin:0; font-size:0.93rem;">Publish content in any language with automatic 2-letter URL routing and full LTR/RTL layout support.</p>
                    </div>
                </div>
                <div class="lf-card lf-card-hover">
                    <div class="lf-card-body">
                        <div class="lf-feature-icon">&#128736;</div>
                        <h3 style="margin:0 0 8px 0;">Shortcodes &amp; Dynamic Forms</h3>
                        <p class="lf-meta" style="margin:0; font-size:0.93rem;">Drop responsive hero sliders and custom validated forms into any page or post with simple shortcodes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Latest Blog Articles Section --}}
    <section class="lf-section">
        <div class="lf-container">
            <div class="lf-section-head">
                <span class="lf-eyebrow">Latest Articles</span>
                <h2>From Our Blog</h2>
                <p>Insights, product updates, and stories published straight from LaFlux CMS.</p>
            </div>

            @if($posts->isEmpty())
                <div class="lf-card"><div class="lf-card-body"><p style="margin:0;">No published posts yet.</p></div></div>
            @else
                <div class="lf-grid-3">
                    @foreach($posts as $post)
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

                @if($posts->hasPages())
                    <div style="margin-top:32px; text-align:center;">{{ $posts->links() }}</div>
                @endif
            @endif
        </div>
    </section>

    {{-- Contact / CTA Section --}}
    @php
        $contactFormHtml = \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render('[form slug="contact-us"]');
    @endphp
    @if(!empty(trim($contactFormHtml)))
        <section class="lf-section lf-section-alt">
            <div class="lf-container">
                <div class="lf-grid-2" style="align-items:center;">
                    <div>
                        <span class="lf-eyebrow">Get In Touch</span>
                        <h2 style="font-size:1.95rem; margin:8px 0 12px 0;">Have a Project in Mind?</h2>
                        <p style="color:var(--lf-muted); margin-bottom:20px;">
                            Send us a message using our dynamic CMS form below. Every submission is validated and stored directly in your LaFlux Admin inbox.
                        </p>
                        <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('page/about-us') }}" class="lf-btn lf-btn-outline">Learn More About Us</a>
                    </div>
                    <div>
                        {!! $contactFormHtml !!}
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

