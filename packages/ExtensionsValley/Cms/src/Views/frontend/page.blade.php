@extends('Cms::frontend.layout')

@section('content')
    <article class="card">
        @if($page->featured_image)
            <img class="featured" src="{{ asset($page->featured_image) }}" alt="{{ $page->title }}">
        @endif
        <h1 style="margin-top:0;">{{ $page->title }}</h1>
        <div class="meta">
            Updated {{ ($page->updated_at ?? $page->created_at)->format('M j, Y') }}
            @if($page->author)
                &middot; by {{ $page->author->name }}
            @endif
        </div>
        <div>@shortcode($page->content)</div>
    </article>
@endsection

