@extends('Dashboard::dashboard.dashboard')
@section('content-header')
    @include('Dashboard::dashboard.partials.headersidebar')
@stop
@section('content-area')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <h2>{{ $title }} <small>Redirect old website URLs to new pages (301 Permanent, 302 Temporary, etc.)</small></h2>
                </div>
            </div>
        </div>
        @php
            $action = isset($redirect) ? 'extensionsvalley.cms.updateredirect' : 'extensionsvalley.cms.saveredirect';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group {{ $errors->has('source_url') ? 'has-error' : '' }}">
                            <label for="source_url">Old URL Path <span class="error_red">*</span></label>
                            <input type="text" name="source_url" id="source_url" class="form-control"
                                   value="{{ old('source_url', $redirect->source_url ?? '') }}"
                                   placeholder="/old-services or /blog/old-article" required {{ $readonly }}>
                            <small class="text-muted">Relative path starting with <code>/</code> (full URLs are normalized automatically).</small>
                            <span class="error_red">{{ $errors->first('source_url') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status_code">Redirect Type <span class="error_red">*</span></label>
                            <select name="status_code" id="status_code" class="form-control" required {{ $disabled }}>
                                <option value="301" {{ old('status_code', $redirect->status_code ?? 301) == 301 ? 'selected' : '' }}>301 — Permanent (SEO)</option>
                                <option value="302" {{ old('status_code', $redirect->status_code ?? 301) == 302 ? 'selected' : '' }}>302 — Temporary</option>
                                <option value="307" {{ old('status_code', $redirect->status_code ?? 301) == 307 ? 'selected' : '' }}>307 — Temporary (Keep Method)</option>
                                <option value="308" {{ old('status_code', $redirect->status_code ?? 301) == 308 ? 'selected' : '' }}>308 — Permanent (Keep Method)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('target_url') ? 'has-error' : '' }}">
                            <label for="target_url">Target URL <span class="error_red">*</span></label>
                            <input type="text" name="target_url" id="target_url" class="form-control"
                                   value="{{ old('target_url', $redirect->target_url ?? '') }}"
                                   placeholder="/en/page/services or https://..." required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('target_url') }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                <option value="1" {{ old('status', $redirect->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $redirect->status ?? 1) == 0 ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if(isset($redirect->id))
                    <input type="hidden" name="redirect_id" value="{{ $redirect->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ request()->get('accesstoken', '') }}"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_redirects') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">Save Redirect</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

