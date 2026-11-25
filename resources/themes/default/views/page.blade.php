@extends('Theme::layout')

@section('hero')
    <section class="lf-page-banner">
        <div class="lf-container">
            <h1>{{ $page->title }}</h1>
            @if(!empty($page->meta_description))
                <p>{{ $page->meta_description }}</p>
            @endif
        </div>
    </section>
@endsection

@section('content')
    <section class="lf-section">
        <div class="lf-container">
            <div class="lf-card">
                <div class="lf-card-body" style="padding:36px;">
                    @if($page->featured_image)
                        <img src="{{ str_starts_with($page->featured_image, 'http') ? $page->featured_image : asset($page->featured_image) }}"
                             alt="{{ $page->title }}"
                             style="width:100%; max-height:420px; object-fit:cover; border-radius:10px; margin-bottom:24px;">
                    @endif
                    <div class="lf-meta" style="margin-bottom:20px;">
                        Updated {{ ($page->updated_at ?? $page->created_at)->format('M j, Y') }}
                        @if($page->author) &middot; by {{ $page->author->name }} @endif
                    </div>
                    <div>@shortcode($page->content)</div>
                </div>
            </div>
        </div>
    </section>
@endsection

