@extends('Dashboard::dashboard.dashboard')
@section('content-header')
    @include('Dashboard::dashboard.partials.headersidebar')
@stop
@section('content-area')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2>{{ $title }} <small>Front-end multilingual URLs activate automatically when 2+ languages are active</small></h2>
                </div>
            </div>
        </div>
        @php
            $action = isset($language) ? 'extensionsvalley.cms.updatelanguage' : 'extensionsvalley.cms.savelanguage';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Language Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $language->name ?? '') }}" placeholder="e.g. Arabic, Spanish, French" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                            <label for="code">2-Letter Code <span class="error_red">*</span></label>
                            <input type="text" name="code" id="code" maxlength="2" class="form-control"
                                   value="{{ old('code', $language->code ?? '') }}" placeholder="en, ar, es" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('code') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="direction">Text Direction <span class="error_red">*</span></label>
                            <select name="direction" id="direction" class="form-control" required {{ $disabled }}>
                                <option value="ltr" {{ old('direction', $language->direction ?? 'ltr') == 'ltr' ? 'selected' : '' }}>LTR (Left to Right)</option>
                                <option value="rtl" {{ old('direction', $language->direction ?? 'ltr') == 'rtl' ? 'selected' : '' }}>RTL (Right to Left)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                <option value="1" {{ old('status', $language->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $language->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_default" value="1"
                                       {{ old('is_default', $language->is_default ?? false) ? 'checked' : '' }} {{ $disabled }}>
                                <strong>Set as Default Language</strong>
                            </label>
                        </div>
                    </div>
                </div>

                @if(isset($language->id))
                    <input type="hidden" name="language_id" value="{{ $language->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ request()->get('accesstoken', '') }}"/>

                <div class="row" style="margin-top:12px;">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_languages') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">Save Language</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

