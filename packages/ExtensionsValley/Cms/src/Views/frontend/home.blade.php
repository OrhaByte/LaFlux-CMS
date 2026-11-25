@extends('Cms::frontend.layout')

@section('content')
    @forelse($posts as $post)
        <article class="card">
            @if($post->featured_image)
                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}">
                    <img class="featured" src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}">
                </a>
            @endif
            <h2 style="margin-top:0;">
                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}">{{ $post->title }}</a>
            </h2>
            <div class="meta">
                Published {{ ($post->published_at ?? $post->created_at)->format('M j, Y') }}
                @if($post->category)
                    &middot; in <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $post->category->slug) }}">{{ $post->category->name }}</a>
                @endif
                @if($post->author)
                    &middot; by {{ $post->author->name }}
                @endif
            </div>
            <p>{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 180) }}</p>
            @if($post->tags->isNotEmpty())
                <div style="margin-bottom:10px;">
                    @foreach($post->tags as $tag)
                        <a class="badge" href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('tag/' . $tag->slug) }}">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif
            <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}">Read more &rarr;</a>
        </article>
    @empty
        <div class="card">
            <p>No published posts yet.</p>
        </div>
    @endforelse

    <div>{{ $posts->links() }}</div>
@endsection

