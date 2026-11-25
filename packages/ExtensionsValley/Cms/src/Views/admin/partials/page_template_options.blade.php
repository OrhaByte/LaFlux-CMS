@php
    $pageTemplates = \ExtensionsValley\Cms\Helpers\ThemeHelper::getPageTemplates();
    $selectedTemplate = old('template', $page->template ?? 'default');
    $savedTplData = old('template_data', isset($page) && is_array($page->template_data) ? $page->template_data : []);

    $tplCategories = \ExtensionsValley\Cms\Models\Category::where('status', 1)->orderBy('language_code')->orderBy('name')->get(['id', 'name', 'language_code']);
    $tplTags = \ExtensionsValley\Cms\Models\Tag::where('status', 1)->orderBy('language_code')->orderBy('name')->get(['id', 'name', 'language_code']);
    $tplBanners = \ExtensionsValley\Cms\Models\Banner::where('status', 1)->orderBy('name')->get(['slug', 'name']);
    $tplForms = \ExtensionsValley\Cms\Models\CmsForm::where('status', 1)->orderBy('name')->get(['slug', 'name']);
    $tplSortOptions = [
        'latest' => 'Latest Published First',
        'oldest' => 'Oldest Published First',
        'title_asc' => 'Title (A → Z)',
        'title_desc' => 'Title (Z → A)',
        'random' => 'Random Order',
    ];
@endphp

@foreach($pageTemplates as $tplKey => $tplDef)
    @if(!empty($tplDef['fields']))
        <div class="panel panel-info cms-template-options-box" data-template-key="{{ $tplKey }}" style="margin-top:16px; display:{{ $selectedTemplate === $tplKey ? 'block' : 'none' }};">
            <div class="panel-heading">
                <strong><i class="fa fa-sliders"></i> {{ $tplDef['label'] ?? ucfirst($tplKey) }} — Template Options</strong>
                @if(!empty($tplDef['description']))
                    <div><small>{{ $tplDef['description'] }}</small></div>
                @endif
            </div>
            <div class="panel-body">
                <div class="row">
                    @foreach($tplDef['fields'] as $fKey => $fDef)
                        @php
                            $fType = $fDef['type'] ?? 'text';
                            $fLabel = $fDef['label'] ?? ucfirst($fKey);
                            $fVal = ($selectedTemplate === $tplKey && array_key_exists($fKey, (array) $savedTplData))
                                ? $savedTplData[$fKey]
                                : ($fDef['default'] ?? '');
                            $fName = "template_data[{$tplKey}][{$fKey}]";
                            $colClass = in_array($fType, ['textarea', 'toggle'], true) ? 'col-md-12' : 'col-md-6';
                        @endphp
                        <div class="{{ $colClass }}">
                            @if($fType === 'toggle')
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="{{ $fName }}" value="1" {{ !empty($fVal) ? 'checked' : '' }} {{ $disabled ?? '' }}>
                                        <strong>{{ $fLabel }}</strong>
                                    </label>
                                </div>
                            @elseif($fType === 'textarea')
                                <div class="form-group">
                                    <label>{{ $fLabel }}</label>
                                    <textarea name="{{ $fName }}" rows="2" class="form-control" {{ $readonly ?? '' }}>{{ $fVal }}</textarea>
                                </div>
                            @elseif($fType === 'category')
                                <div class="form-group">
                                    <label>{{ $fLabel }}</label>
                                    <select name="{{ $fName }}" class="form-control" {{ $disabled ?? '' }}>
                                        <option value="">— All Categories —</option>
                                        @foreach($tplCategories as $cat)
                                            <option value="{{ $cat->id }}" {{ (string) $fVal === (string) $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }} ({{ strtoupper($cat->language_code ?? 'en') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif($fType === 'tag')
                                <div class="form-group">
                                    <label>{{ $fLabel }}</label>
                                    <select name="{{ $fName }}" class="form-control" {{ $disabled ?? '' }}>
                                        <option value="">— Any Tag —</option>
                                        @foreach($tplTags as $t)
                                            <option value="{{ $t->id }}" {{ (string) $fVal === (string) $t->id ? 'selected' : '' }}>
                                                #{{ $t->name }} ({{ strtoupper($t->language_code ?? 'en') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif($fType === 'sort')
                                <div class="form-group">
                                    <label>{{ $fLabel }}</label>
                                    <select name="{{ $fName }}" class="form-control" {{ $disabled ?? '' }}>
                                        @foreach($tplSortOptions as $sKey => $sLabel)
                                            <option value="{{ $sKey }}" {{ (string) $fVal === (string) $sKey ? 'selected' : '' }}>{{ $sLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif($fType === 'banner')
                                <div class="form-group">
                                    <label>{{ $fLabel }}</label>
                                    <select name="{{ $fName }}" class="form-control" {{ $disabled ?? '' }}>
                                        <option value="">— None —</option>
                                        @foreach($tplBanners as $b)
                                            <option value="{{ $b->slug }}" {{ (string) $fVal === (string) $b->slug ? 'selected' : '' }}>{{ $b->name }} ({{ $b->slug }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif($fType === 'form')
                                <div class="form-group">
                                    <label>{{ $fLabel }}</label>
                                    <select name="{{ $fName }}" class="form-control" {{ $disabled ?? '' }}>
                                        <option value="">— None —</option>
                                        @foreach($tplForms as $f)
                                            <option value="{{ $f->slug }}" {{ (string) $fVal === (string) $f->slug ? 'selected' : '' }}>{{ $f->name }} ({{ $f->slug }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif($fType === 'number')
                                <div class="form-group">
                                    <label>{{ $fLabel }}</label>
                                    <input type="number" name="{{ $fName }}" min="1" max="30" class="form-control" value="{{ $fVal }}" {{ $readonly ?? '' }}>
                                </div>
                            @else
                                <div class="form-group">
                                    <label>{{ $fLabel }}</label>
                                    <input type="text" name="{{ $fName }}" class="form-control" value="{{ $fVal }}" {{ $readonly ?? '' }}>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach

@push('scripts')
<script>
    function togglePageTemplateOptions() {
        var sel = document.getElementById('page_template_select');
        if (!sel) return;
        var val = sel.value;
        document.querySelectorAll('.cms-template-options-box').forEach(function (box) {
            box.style.display = (box.getAttribute('data-template-key') === val) ? 'block' : 'none';
        });
    }
</script>
@endpush

