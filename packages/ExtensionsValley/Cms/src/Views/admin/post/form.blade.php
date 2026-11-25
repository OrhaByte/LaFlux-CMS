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
            $action = isset($post) ? 'extensionsvalley.cms.updatepost' : 'extensionsvalley.cms.savepost';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
            $publishedAtVal = old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'));
            $oldTags = old('tags', $selectedTags ?? []);
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title">Post Title <span class="error_red">*</span></label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title', $post->title ?? '') }}" placeholder="Post Title" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('title') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">
                                Slug
                                @if(!isset($post))
                                    <small class="text-muted">(leave blank to auto-generate)</small>
                                @endif
                            </label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug', $post->slug ?? '') }}" placeholder="post-slug" {{ $readonly }}>
                            @if(isset($post) && !empty($post->slug))
                                @php $postUrl = \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug, $post->language_code); @endphp
                                <div style="margin-top:6px; font-size:12.5px;">
                                    <strong>Permalink:</strong>
                                    <a href="{{ $postUrl }}" target="_blank" id="cmsLiveUrl">{{ $postUrl }} <i class="fa fa-external-link"></i></a>
                                    <button type="button" class="btn btn-xs btn-default" style="margin-left:6px;" onclick="navigator.clipboard && navigator.clipboard.writeText(document.getElementById('cmsLiveUrl').href); alert('URL copied!');">Copy</button>
                                </div>
                            @endif
                            <span class="error_red">{{ $errors->first('slug') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="excerpt">Excerpt</label>
                            <textarea name="excerpt" id="excerpt" rows="3" class="form-control" placeholder="Short summary..." {{ $readonly }}>{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
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
                            <textarea name="content" id="content" class="form-control cms-editor" rows="12" {{ $readonly }}>{{ old('content', $post->content ?? '') }}</textarea>
                        </div>

                        <hr/>
                        <h4>SEO Meta</h4>
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                   value="{{ old('meta_title', $post->meta_title ?? '') }}" {{ $readonly }}>
                        </div>
                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords <small class="text-muted">(comma separated)</small></label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                   value="{{ old('meta_keywords', $post->meta_keywords ?? '') }}" placeholder="keyword1, keyword2, keyword3" {{ $readonly }}>
                        </div>
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" class="form-control" {{ $readonly }}>{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Publishing</strong></div>
                            <div class="panel-body">
                                @include('Cms::admin.partials.language_select', ['currentLanguageCode' => $post->language_code ?? null])
                                <div class="form-group">
                                    <label for="status">Status <span class="error_red">*</span></label>
                                    <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                        <option value="1" {{ old('status', $post->status ?? 1) == 1 ? 'selected' : '' }}>Publish</option>
                                        <option value="0" {{ old('status', $post->status ?? 1) == 0 ? 'selected' : '' }}>Draft / Unpublish</option>
                                    </select>
                                </div>
                                <div class="form-group {{ $errors->has('published_at') ? 'has-error' : '' }}">
                                    <label for="published_at">Publish Date &amp; Time <small class="text-muted">(future date = scheduled)</small></label>
                                    <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                                           value="{{ $publishedAtVal }}" {{ $readonly }}>
                                    <span class="error_red">{{ $errors->first('published_at') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Category &amp; Tags</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control select2" {{ $disabled }}>
                                        <option value="">— Uncategorized —</option>
                                        @foreach($categories as $cid => $cname)
                                            <option value="{{ $cid }}" {{ old('category_id', $post->category_id ?? '') == $cid ? 'selected' : '' }}>{{ $cname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tags">Tags</label>
                                    <select name="tags[]" id="tags" class="form-control select2" multiple="multiple" {{ $disabled }}>
                                        @foreach($tags as $tid => $tname)
                                            <option value="{{ $tid }}" {{ in_array($tid, (array) $oldTags) ? 'selected' : '' }}>{{ $tname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Featured Image</strong></div>
                            <div class="panel-body">
                                <div id="featuredImagePreviewBox" style="margin-bottom:10px;">
                                    @if(!empty($post->featured_image))
                                        <img src="{{ asset($post->featured_image) }}" alt="Featured Image" class="img-responsive img-thumbnail" style="max-height:180px;">
                                    @endif
                                </div>
                                @if(!empty($post->featured_image) && !isset($viewmode))
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

                @if(isset($post->id))
                    <input type="hidden" name="post_id" value="{{ $post->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ request()->get('accesstoken', '') }}"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_posts') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">Save Post</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('Cms::admin.partials.tinymce')
    @include('Cms::admin.partials.mediapicker')
@stop

