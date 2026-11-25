@php
    $siteName = \WebConf::get('site_name') ?: 'LaFlux CMS';
    $homeUrl = \ExtensionsValley\Cms\Helpers\CmsHelper::url('');
@endphp
<header class="lf-header">
    <div class="lf-container">
        <div class="lf-nav-wrap">
            <a class="lf-brand" href="{{ $homeUrl }}">
                @if(\WebConf::get('site_logo') != "")
                    <img src="{{ asset(\WebConf::get('site_logo')) }}" alt="{{ $siteName }}" style="max-height:34px;">
                @else
                    <span class="lf-brand-badge">L</span>
                @endif
                <span>{{ $siteName }}</span>
            </a>

            <button type="button" class="lf-mobile-toggle" onclick="document.getElementById('lfNavBox').classList.toggle('open')" aria-label="Toggle Menu">&#9776;</button>

            <nav class="lf-nav-menu-box" id="lfNavBox">
                <ul class="lf-nav">
                    @if(!empty($headerMenu) && $headerMenu->rootItems->isNotEmpty())
                        @foreach($headerMenu->rootItems as $item)
                            <li>
                                <a href="{{ $item->resolved_url }}" target="{{ $item->target }}">{{ $item->title }}</a>
                                @if($item->children->isNotEmpty())
                                    <ul>
                                        @foreach($item->children as $child)
                                            <li><a href="{{ $child->resolved_url }}" target="{{ $child->target }}">{{ $child->title }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    @else
                        <li><a href="{{ $homeUrl }}">Home</a></li>
                        <li><a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('cms') }}">Blog</a></li>
                    @endif

                    @if(!empty($isMultilingual) && !empty($activeLanguages))
                        <li>
                            <a href="javascript:;" class="lf-lang-pill">{{ strtoupper($currentLang->code ?? 'en') }} &#9662;</a>
                            <ul>
                                @foreach($activeLanguages as $langOpt)
                                    <li>
                                        <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('', $langOpt->code) }}">
                                            {{ $langOpt->name }} ({{ strtoupper($langOpt->code) }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</header>

