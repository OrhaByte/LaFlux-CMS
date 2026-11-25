@extends('Theme::layout')

@section('hero')
    <section class="lf-page-banner">
        <div class="lf-container">
            <span class="lf-pill" style="background:rgba(255,255,255,0.16); color:#fff;">Category</span>
            <h1 style="margin-top:8px;">{{ $category->name }}</h1>
            @if($category->description)
                <p>{{ $category->description }}</p>
            @endif
        </div>
    </section>
@endsection

@section('content')
    <section class="lf-section">
        <div class="lf-container">
            <div class="lf-layout-sidebar">
                <div>
                    @forelse($posts as $post)
                        <article class="lf-card lf-card-hover" style="margin-bottom:22px;">
                            <div class="lf-card-body">
                                <h2 style="margin:0 0 8px 0; font-size:1.35rem;">
                                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}" style="color:var(--lf-dark);">{{ $post->title }}</a>
                                </h2>
                                <div class="lf-meta">Published {{ ($post->published_at ?? $post->created_at)->format('M j, Y') }}</div>
                                <p style="color:var(--lf-slate);">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 180) }}</p>
                                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}" style="font-weight:600;">Read more &rarr;</a>
                            </div>
                        </article>
                    @empty
                        <div class="lf-card"><div class="lf-card-body"><p style="margin:0;">No posts in this category yet.</p></div></div>
                    @endforelse
                    <div>{{ $posts->links() }}</div>
                </div>

                @include('Theme::partials.sidebar')
            </div>
        </div>
    </section>
@endsection

