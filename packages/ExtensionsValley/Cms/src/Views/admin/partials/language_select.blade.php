@php
    $cmsLanguages = \ExtensionsValley\Cms\Helpers\CmsHelper::activeLanguages();
    $selectedLang = old('language_code', $currentLanguageCode ?? \ExtensionsValley\Cms\Helpers\CmsHelper::defaultLanguageCode());
@endphp
<div class="form-group {{ $errors->has('language_code') ? 'has-error' : '' }}">
    <label for="language_code">Language <span class="error_red">*</span></label>
    <select name="language_code" id="language_code" class="form-control" required {{ $disabled ?? '' }}>
        @foreach($cmsLanguages as $langItem)
            <option value="{{ $langItem->code }}" {{ $selectedLang === $langItem->code ? 'selected' : '' }}>
                {{ $langItem->name }} ({{ strtoupper($langItem->code) }})
            </option>
        @endforeach
    </select>
    <span class="error_red">{{ $errors->first('language_code') }}</span>
</div>

