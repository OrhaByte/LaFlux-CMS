@php
    $siteName = \WebConf::get('site_name') ?: 'LaFlux CMS';
    $brandingCfg = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSection('branding', $currentLang->code ?? null);
@endphp
<footer class="lf-footer">
    <div class="lf-container">
        <div class="lf-footer-grid">
            <div>
                <a class="lf-brand" href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('') }}" style="margin-bottom:12px;">
                    <span class="lf-brand-badge">L</span>
                    <span>{{ $siteName }}</span>
                </a>
                <p style="font-size:0.9rem; margin:10px 0 0 0; max-width:320px;">
                    {{ $brandingCfg['footer_tagline'] ?? 'Modern modular Laravel 13 CMS.' }}
                </p>
            </div>

            <div>
                <h4>Navigation</h4>
                <ul>
                    @if(!empty($footerMenu) && $footerMenu->rootItems->isNotEmpty())
                        @foreach($footerMenu->rootItems as $item)
                            <li><a href="{{ $item->resolved_url }}" target="{{ $item->target }}">{{ $item->title }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('') }}">Home</a></li>
                        <li><a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('cms') }}">Blog</a></li>
                    @endif
                </ul>
            </div>

            <div>
                <h4>Categories</h4>
                <ul>
                    @forelse(($sidebarCategories ?? collect())->take(5) as $cat)
                        <li><a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $cat->slug) }}">{{ $cat->name }}</a></li>
                    @empty
                        <li><span style="font-size:0.88rem;">No categories</span></li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h4>Resources</h4>
                <ul>
                    <li><a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('cms') }}">Latest Articles</a></li>
                    <li><a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('page/contact-us') }}">Contact Us</a></li>
                </ul>
            </div>
        </div>

        <div class="lf-footer-bottom">
            <div>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
            <div>Powered By <strong style="color:#fff;">OrhaByte Software Labs LLP</strong></div>
        </div>
    </div>
</footer>

