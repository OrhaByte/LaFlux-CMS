@extends('Dashboard::dashboard.dashboard')
@section('content-header')
    @include('Dashboard::dashboard.partials.headersidebar')
@stop
@section('content-area')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2>{{ $title }}</h2>
                </div>
            </div>
        </div>
        @php
            $action = isset($tag) ? 'extensionsvalley.cms.updatetag' : 'extensionsvalley.cms.savetag';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Tag Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $tag->name ?? '') }}" placeholder="Tag Name" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">Slug <small class="text-muted">(auto if blank)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug', $tag->slug ?? '') }}" placeholder="tag-slug" {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('slug') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @include('Cms::admin.partials.language_select', ['currentLanguageCode' => $tag->language_code ?? null])
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                <option value="1" {{ old('status', $tag->status ?? 1) == 1 ? 'selected' : '' }}>Publish</option>
                                <option value="0" {{ old('status', $tag->status ?? 1) == 0 ? 'selected' : '' }}>Unpublish</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if(isset($tag->id))
                    <input type="hidden" name="tag_id" value="{{ $tag->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ request()->get('accesstoken', '') }}"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_tags') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">Submit</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

