@extends('Cms::frontend.layout')

@section('content')
    <article class="card">
        @if($post->featured_image)
            <img class="featured" src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}">
        @endif
        <h1 style="margin-top:0;">{{ $post->title }}</h1>
        <div class="meta">
            Published {{ ($post->published_at ?? $post->created_at)->format('M j, Y') }}
            @if($post->category)
                &middot; in <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $post->category->slug) }}">{{ $post->category->name }}</a>
            @endif
            @if($post->author)
                &middot; by {{ $post->author->name }}
            @endif
        </div>
        <div>@shortcode($post->content)</div>
        @if($post->tags->isNotEmpty())
            <hr style="margin:20px 0;border:0;border-top:1px solid #e2e8f0;">
            <div>
                @foreach($post->tags as $tag)
                    <a class="badge" href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('tag/' . $tag->slug) }}">#{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif
    </article>
@endsection

