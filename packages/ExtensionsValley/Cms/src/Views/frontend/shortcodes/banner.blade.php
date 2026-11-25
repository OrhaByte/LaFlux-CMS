@php $uid = 'cms-banner-' . $banner->id . '-' . uniqid(); @endphp
<div class="cms-slider" id="{{ $uid }}" style="position:relative; overflow:hidden; border-radius:10px; margin:20px 0; background:#0f172a; box-shadow:0 8px 24px rgba(0,0,0,0.12);">
    @foreach($banner->activeSlides as $index => $slide)
        <div class="cms-slide" style="display:{{ $index === 0 ? 'block' : 'none' }}; position:relative;">
            <img src="{{ str_starts_with($slide->image, 'http') ? $slide->image : asset($slide->image) }}"
                 alt="{{ $slide->title ?? $banner->name }}"
                 style="width:100%; height:380px; object-fit:cover; display:block;">
            @if($slide->title || $slide->description || $slide->button_link)
                <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.25) 60%, transparent 100%); display:flex; align-items:flex-end; padding:28px 32px; color:#fff;">
                    <div style="max-width:640px;">
                        @if($slide->title)
                            <h2 style="margin:0 0 8px 0; font-size:1.75rem; color:#fff;">{{ $slide->title }}</h2>
                        @endif
                        @if($slide->description)
                            <p style="margin:0 0 14px 0; color:#e2e8f0; font-size:1rem;">{{ $slide->description }}</p>
                        @endif
                        @if($slide->button_link)
                            @php
                                $btnHref = (str_starts_with($slide->button_link, '/') && !str_starts_with($slide->button_link, '//'))
                                    ? url($slide->button_link)
                                    : $slide->button_link;
                            @endphp
                            <a href="{{ $btnHref }}" style="display:inline-block; background:#2563eb; color:#fff; padding:9px 18px; border-radius:6px; font-weight:600; text-decoration:none;">
                                {{ $slide->button_text ?: 'Read More' }} &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    @if($banner->activeSlides->count() > 1)
        <button type="button" class="cms-prev" aria-label="Previous slide"
                style="position:absolute; left:12px; top:50%; transform:translateY(-50%); background:rgba(15,23,42,0.65); color:#fff; border:0; width:38px; height:38px; border-radius:50%; cursor:pointer; font-size:18px;">&#10094;</button>
        <button type="button" class="cms-next" aria-label="Next slide"
                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:rgba(15,23,42,0.65); color:#fff; border:0; width:38px; height:38px; border-radius:50%; cursor:pointer; font-size:18px;">&#10095;</button>

        <div class="cms-dots" style="position:absolute; bottom:12px; right:20px; display:flex; gap:8px;">
            @foreach($banner->activeSlides as $index => $slide)
                <button type="button" class="cms-dot" data-index="{{ $index }}"
                        style="width:10px; height:10px; border-radius:50%; border:0; cursor:pointer; background:{{ $index === 0 ? '#ffffff' : 'rgba(255,255,255,0.45)' }};"></button>
            @endforeach
        </div>

        <script>
            (function () {
                var root = document.getElementById('{{ $uid }}');
                if (!root) return;
                var slides = root.querySelectorAll('.cms-slide');
                var dots = root.querySelectorAll('.cms-dot');
                var current = 0;

                function show(idx) {
                    current = (idx + slides.length) % slides.length;
                    slides.forEach(function (s, i) { s.style.display = i === current ? 'block' : 'none'; });
                    dots.forEach(function (d, i) { d.style.background = i === current ? '#ffffff' : 'rgba(255,255,255,0.45)'; });
                }

                root.querySelector('.cms-prev').addEventListener('click', function () { show(current - 1); });
                root.querySelector('.cms-next').addEventListener('click', function () { show(current + 1); });
                dots.forEach(function (d) {
                    d.addEventListener('click', function () { show(parseInt(d.getAttribute('data-index'), 10)); });
                });

                setInterval(function () { show(current + 1); }, 5500);
            })();
        </script>
    @endif
</div>

