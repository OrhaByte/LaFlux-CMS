@extends('Cms::frontend.layout')

@section('content')
    <div class="card">
        <h1 style="margin:0 0 8px 0;">Category: {{ $category->name }}</h1>
        @if($category->description)
            <p class="meta" style="margin:0;">{{ $category->description }}</p>
        @endif
    </div>

    @forelse($posts as $post)
        <article class="card">
            <h2 style="margin-top:0;">
                <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}">{{ $post->title }}</a>
            </h2>
            <div class="meta">Published {{ ($post->published_at ?? $post->created_at)->format('M j, Y') }}</div>
            <p>{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 180) }}</p>
            <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}">Read more &rarr;</a>
        </article>
    @empty
        <div class="card"><p>No posts in this category yet.</p></div>
    @endforelse

    <div>{{ $posts->links() }}</div>
@endsection

