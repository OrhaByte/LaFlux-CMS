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
            $action = isset($category) ? 'extensionsvalley.cms.updatecategory' : 'extensionsvalley.cms.savecategory';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Category Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $category->name ?? '') }}" placeholder="Category Name" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">Slug <small class="text-muted">(leave blank to auto-generate)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug', $category->slug ?? '') }}" placeholder="category-slug" {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('slug') }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        @include('Cms::admin.partials.language_select', ['currentLanguageCode' => $category->language_code ?? null])
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            <label for="parent_id">Parent Category</label>
                            <select name="parent_id" id="parent_id" class="form-control select2" {{ $disabled }}>
                                <option value="">— None (Top Level) —</option>
                                @foreach($parents as $pid => $pname)
                                    <option value="{{ $pid }}" {{ old('parent_id', $category->parent_id ?? '') == $pid ? 'selected' : '' }}>{{ $pname }}</option>
                                @endforeach
                            </select>
                            <span class="error_red">{{ $errors->first('parent_id') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Publish</option>
                                <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Unpublish</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control" {{ $readonly }}>{{ old('description', $category->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <hr/>
                <h4>SEO Meta</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                   value="{{ old('meta_title', $category->meta_title ?? '') }}" {{ $readonly }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords <small class="text-muted">(comma separated)</small></label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                   value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}" placeholder="laravel, cms, news" {{ $readonly }}>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="2" class="form-control" {{ $readonly }}>{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                @if(isset($category->id))
                    <input type="hidden" name="category_id" value="{{ $category->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ request()->get('accesstoken', '') }}"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_categories') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">Submit</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

