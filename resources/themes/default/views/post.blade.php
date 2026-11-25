@extends('Theme::layout')

@section('hero')
    <section class="lf-page-banner">
        <div class="lf-container">
            @if($post->category)
                <a class="lf-pill" style="background:rgba(255,255,255,0.16); color:#fff;"
                   href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $post->category->slug) }}">
                    {{ $post->category->name }}
                </a>
            @endif
            <h1 style="margin-top:8px;">{{ $post->title }}</h1>
            <p>
                Published {{ ($post->published_at ?? $post->created_at)->format('M j, Y') }}
                @if($post->author) &middot; by {{ $post->author->name }} @endif
            </p>
        </div>
    </section>
@endsection

@section('content')
    <section class="lf-section">
        <div class="lf-container">
            <div class="lf-layout-sidebar">
                <article class="lf-card">
                    <div class="lf-card-body" style="padding:34px;">
                        @if($post->featured_image)
                            <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset($post->featured_image) }}"
                                 alt="{{ $post->title }}"
                                 style="width:100%; max-height:400px; object-fit:cover; border-radius:10px; margin-bottom:24px;">
                        @endif
                        <div>@shortcode($post->content)</div>

                        @if($post->tags->isNotEmpty())
                            <hr style="margin:28px 0 18px; border:0; border-top:1px solid var(--lf-border);">
                            <div>
                                @foreach($post->tags as $tag)
                                    <a class="lf-pill" href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('tag/' . $tag->slug) }}">#{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>

                @include('Theme::partials.sidebar')
            </div>
        </div>
    </section>
@endsection

