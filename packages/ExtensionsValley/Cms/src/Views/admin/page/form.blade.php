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
            $action = isset($page) ? 'extensionsvalley.cms.updatepage' : 'extensionsvalley.cms.savepage';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
            $publishedAtVal = old('published_at', isset($page->published_at) ? $page->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'));
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title">Page Title <span class="error_red">*</span></label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title', $page->title ?? '') }}" placeholder="Page Title" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('title') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">
                                Slug
                                @if(!isset($page))
                                    <small class="text-muted">(leave blank to auto-generate)</small>
                                @endif
                            </label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug', $page->slug ?? '') }}" placeholder="page-slug" {{ $readonly }}>
                            @if(isset($page) && !empty($page->slug))
                                @php $pageUrl = \ExtensionsValley\Cms\Helpers\CmsHelper::url('page/' . $page->slug, $page->language_code); @endphp
                                <div style="margin-top:6px; font-size:12.5px;">
                                    <strong>Permalink:</strong>
                                    <a href="{{ $pageUrl }}" target="_blank" id="cmsLiveUrl">{{ $pageUrl }} <i class="fa fa-external-link"></i></a>
                                    <button type="button" class="btn btn-xs btn-default" style="margin-left:6px;" onclick="navigator.clipboard && navigator.clipboard.writeText(document.getElementById('cmsLiveUrl').href); alert('URL copied!');">Copy</button>
                                </div>
                            @endif
                            <span class="error_red">{{ $errors->first('slug') }}</span>
                        </div>

                        <div class="form-group">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                                <label for="content" style="margin:0;">Content</label>
                                @if(!isset($viewmode))
                                    <button type="button" class="btn btn-default btn-xs" onclick="openMediaPicker('editor')">
                                        <i class="fa fa-picture-o"></i> Add Media to Content
                                    </button>
                                @endif
                            </div>
                            <textarea name="content" id="content" class="form-control cms-editor" rows="12" {{ $readonly }}>{{ old('content', $page->content ?? '') }}</textarea>
                        </div>

                        <hr/>
                        <h4>SEO Meta</h4>
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                   value="{{ old('meta_title', $page->meta_title ?? '') }}" {{ $readonly }}>
                        </div>
                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords <small class="text-muted">(comma separated)</small></label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                   value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}" placeholder="keyword1, keyword2, keyword3" {{ $readonly }}>
                        </div>
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" class="form-control" {{ $readonly }}>{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Publishing</strong></div>
                            <div class="panel-body">
                                @include('Cms::admin.partials.language_select', ['currentLanguageCode' => $page->language_code ?? null])
                                <div class="form-group">
                                    <label for="status">Status <span class="error_red">*</span></label>
                                    <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                        <option value="1" {{ old('status', $page->status ?? 1) == 1 ? 'selected' : '' }}>Publish</option>
                                        <option value="0" {{ old('status', $page->status ?? 1) == 0 ? 'selected' : '' }}>Draft / Unpublish</option>
                                    </select>
                                </div>
                                <div class="form-group {{ $errors->has('published_at') ? 'has-error' : '' }}">
                                    <label for="published_at">Publish Date &amp; Time <small class="text-muted">(future date = scheduled)</small></label>
                                    <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                                           value="{{ $publishedAtVal }}" {{ $readonly }}>
                                    <span class="error_red">{{ $errors->first('published_at') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="ordering">Page Order</label>
                                    <input type="number" name="ordering" id="ordering" class="form-control"
                                           value="{{ old('ordering', $page->ordering ?? 0) }}" {{ $readonly }}>
                                </div>
                            </div>
                        </div>

                        @if(isset($page))
                            @include('Cms::admin.partials.versions', ['versions' => $page->versions])
                        @endif

                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Featured Image</strong></div>
                            <div class="panel-body">
                                <div id="featuredImagePreviewBox" style="margin-bottom:10px;">
                                    @if(!empty($page->featured_image))
                                        <img src="{{ asset($page->featured_image) }}" alt="Featured Image" class="img-responsive img-thumbnail" style="max-height:180px;">
                                    @endif
                                </div>
                                @if(!empty($page->featured_image) && !isset($viewmode))
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="remove_featured_image" value="1"> Remove current image</label>
                                    </div>
                                @endif
                                @if(!isset($viewmode))
                                    <input type="hidden" name="selected_media_path" id="selected_media_path" value="">
                                    <button type="button" class="btn btn-default btn-sm btn-block" style="margin-bottom:8px;" onclick="openMediaPicker()">
                                        <i class="fa fa-picture-o"></i> Choose from Media Library
                                    </button>
                                    <input type="file" name="featured_image" id="featured_image" class="form-control" accept="image/*">
                                    <span class="error_red">{{ $errors->first('featured_image') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($page->id))
                    <input type="hidden" name="page_id" value="{{ $page->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ request()->get('accesstoken', '') }}"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_pages') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">Save Page</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('Cms::admin.partials.tinymce')
    @include('Cms::admin.partials.mediapicker')
@stop

