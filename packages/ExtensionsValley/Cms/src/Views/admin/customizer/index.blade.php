@extends('Dashboard::dashboard.dashboard')
@section('content-header')
    @include('Dashboard::dashboard.partials.headersidebar')
@stop
@section('content-area')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <h2 style="margin:0;"><i class="fa fa-paint-brush"></i> {{ $title }}</h2>
                            <small class="text-muted">Configure homepage sections, category/tag post sources, sort order, banners, and forms per language.</small>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                            <form method="GET" action="{{ route('extensionsvalley.cms.customizer') }}" class="form-inline" style="display:inline-flex; gap:6px; align-items:center;">
                                <label style="margin:0; font-size:12px;">Theme:</label>
                                <select name="theme" class="form-control input-sm" onchange="this.form.submit()">
                                    @foreach($themes as $tSlug => $tMeta)
                                        <option value="{{ $tSlug }}" {{ $selectedTheme === $tSlug ? 'selected' : '' }}>
                                            {{ $tMeta['name'] ?? ucfirst($tSlug) }}{{ $tSlug === $activeTheme ? ' (Active)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="lang" value="{{ $selectedLang }}">
                            </form>

                            <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('', $selectedLang) }}" target="_blank" class="btn btn-default btn-sm">
                                <i class="fa fa-external-link"></i> View Live ({{ strtoupper($selectedLang) }})
                            </a>
                        </div>
                    </div>

                    {{-- Language Tabs --}}
                    <ul class="nav nav-tabs" style="margin-top:16px;">
                        @foreach($languages as $langOpt)
                            <li class="{{ $selectedLang === $langOpt->code ? 'active' : '' }}">
                                <a href="{{ route('extensionsvalley.cms.customizer', ['theme' => $selectedTheme, 'lang' => $langOpt->code]) }}">
                                    <i class="fa fa-globe"></i> {{ $langOpt->name }} ({{ strtoupper($langOpt->code) }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <form action="{{ route('extensionsvalley.cms.customizer.save') }}" method="POST">
            @csrf
            <input type="hidden" name="theme_slug" value="{{ $selectedTheme }}">
            <input type="hidden" name="language_code" value="{{ $selectedLang }}">

            @if($selectedTheme !== $activeTheme)
                <div class="alert alert-info">
                    <label style="margin:0; cursor:pointer;">
                        <input type="checkbox" name="activate_theme" value="1">
                        <strong>Activate "{{ $schema['name'] ?? $selectedTheme }}" theme</strong> on the live site when saving
                    </label>
                </div>
            @endif

            @forelse(($schema['sections'] ?? []) as $sectionKey => $sectionDef)
                @php $vals = $sectionValues[$sectionKey] ?? []; @endphp
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>
                                    {{ $sectionDef['label'] ?? ucfirst($sectionKey) }}
                                    @if(!empty($sectionDef['description']))
                                        <small>{{ $sectionDef['description'] }}</small>
                                    @endif
                                </h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    @foreach(($sectionDef['fields'] ?? []) as $fieldKey => $fieldDef)
                                        @php
                                            $type = $fieldDef['type'] ?? 'text';
                                            $label = $fieldDef['label'] ?? ucfirst($fieldKey);
                                            $val = $vals[$fieldKey] ?? ($fieldDef['default'] ?? '');
                                            $inputName = "sections[{$sectionKey}][{$fieldKey}]";
                                            $colClass = in_array($type, ['textarea', 'toggle'], true) ? 'col-md-12' : 'col-md-6';
                                        @endphp
                                        <div class="{{ $colClass }}">
                                            @if($type === 'toggle')
                                                <div class="checkbox" style="margin-bottom:16px;">
                                                    <label>
                                                        <input type="checkbox" name="{{ $inputName }}" value="1" {{ !empty($val) ? 'checked' : '' }}>
                                                        <strong>{{ $label }}</strong>
                                                    </label>
                                                </div>
                                            @elseif($type === 'textarea')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <textarea name="{{ $inputName }}" rows="2" class="form-control">{{ $val }}</textarea>
                                                </div>
                                            @elseif($type === 'category')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <select name="{{ $inputName }}" class="form-control">
                                                        <option value="">— All Categories ({{ strtoupper($selectedLang) }}) —</option>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat->id }}" {{ (string) $val === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif($type === 'tag')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <select name="{{ $inputName }}" class="form-control">
                                                        <option value="">— Any Tag —</option>
                                                        @foreach($tags as $t)
                                                            <option value="{{ $t->id }}" {{ (string) $val === (string) $t->id ? 'selected' : '' }}>#{{ $t->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif($type === 'sort')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <select name="{{ $inputName }}" class="form-control">
                                                        @foreach($sortOptions as $sKey => $sLabel)
                                                            <option value="{{ $sKey }}" {{ (string) $val === (string) $sKey ? 'selected' : '' }}>{{ $sLabel }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif($type === 'banner')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <select name="{{ $inputName }}" class="form-control">
                                                        <option value="">— None (Use Fallback Heading) —</option>
                                                        @foreach($banners as $b)
                                                            <option value="{{ $b->slug }}" {{ (string) $val === (string) $b->slug ? 'selected' : '' }}>{{ $b->name }} ({{ $b->slug }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif($type === 'form')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <select name="{{ $inputName }}" class="form-control">
                                                        <option value="">— None —</option>
                                                        @foreach($forms as $f)
                                                            <option value="{{ $f->slug }}" {{ (string) $val === (string) $f->slug ? 'selected' : '' }}>{{ $f->name }} ({{ $f->slug }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif($type === 'page')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <select name="{{ $inputName }}" class="form-control">
                                                        <option value="">— None —</option>
                                                        @foreach($pages as $p)
                                                            <option value="{{ $p->slug }}" {{ (string) $val === (string) $p->slug ? 'selected' : '' }}>{{ $p->title }} (/page/{{ $p->slug }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif($type === 'number')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <input type="number" name="{{ $inputName }}" min="1" max="30" class="form-control" value="{{ $val }}">
                                                </div>
                                            @elseif($type === 'color')
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <input type="color" name="{{ $inputName }}" class="form-control" style="height:38px; padding:3px; width:100px;" value="{{ $val ?: '#2563eb' }}">
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <input type="text" name="{{ $inputName }}" class="form-control" value="{{ $val }}">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">This theme does not define any customizable sections in its <code>theme.json</code>.</div>
            @endforelse

            <div class="x_panel">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Save Customizer Settings ({{ strtoupper($selectedLang) }})
                    </button>
                    <button type="button" class="btn btn-default btn-sm" onclick="if(confirm('Reset all customizer settings for {{ strtoupper($selectedLang) }} back to theme defaults?')){ document.getElementById('resetCustomizerForm').submit(); }">
                        <i class="fa fa-refresh"></i> Reset to Defaults
                    </button>
                </div>
            </div>
        </form>

        <form id="resetCustomizerForm" action="{{ route('extensionsvalley.cms.customizer.reset') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="theme_slug" value="{{ $selectedTheme }}">
            <input type="hidden" name="language_code" value="{{ $selectedLang }}">
        </form>
    </div>
@stop

