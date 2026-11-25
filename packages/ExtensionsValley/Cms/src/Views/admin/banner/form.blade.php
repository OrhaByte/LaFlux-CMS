@extends('Dashboard::dashboard.dashboard')
@section('content-header')
    @include('Dashboard::dashboard.partials.headersidebar')
@stop
@section('content-area')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2>
                        {{ $title }}
                        @if(isset($banner))
                            <small style="margin-left:12px;">
                                Shortcode:
                                <code id="bannerShortcode">[banner slug="{{ $banner->slug }}"]</code>
                                <button type="button" class="btn btn-xs btn-default" onclick="navigator.clipboard && navigator.clipboard.writeText(document.getElementById('bannerShortcode').textContent); alert('Shortcode copied!');">Copy</button>
                            </small>
                        @endif
                    </h2>
                </div>
            </div>
        </div>
        @php
            $action = isset($banner) ? 'extensionsvalley.cms.updatebanner' : 'extensionsvalley.cms.savebanner';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
            $token = request()->get('accesstoken', base64_encode('extensionsvalley.cms.banners'));
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Banner Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $banner->name ?? '') }}" placeholder="Homepage Slider" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">Slug <small class="text-muted">(used in shortcode)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug', $banner->slug ?? '') }}" placeholder="home-banner" {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('slug') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                <option value="1" {{ old('status', $banner->status ?? 1) == 1 ? 'selected' : '' }}>Publish</option>
                                <option value="0" {{ old('status', $banner->status ?? 1) == 0 ? 'selected' : '' }}>Unpublish</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if(isset($banner->id))
                    <input type="hidden" name="banner_id" value="{{ $banner->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ $token }}"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_banners') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">{{ isset($banner) ? 'Update Banner' : 'Create Banner & Add Slides' }}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if(isset($banner))
            <div class="row">
                @if(!isset($viewmode))
                    <div class="col-md-5">
                        <div class="x_panel">
                            <div class="x_title">
                                <h3>Add Slide</h3>
                                <div class="clearfix"></div>
                            </div>
                            <form action="{{ route('extensionsvalley.cms.savebannerslide') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="banner_id" value="{{ $banner->id }}"/>
                                <input type="hidden" name="accesstoken" value="{{ $token }}"/>

                                <div class="form-group">
                                    <label for="slide_title">Slide Title</label>
                                    <input type="text" name="title" id="slide_title" class="form-control" placeholder="Welcome to LaFlux">
                                </div>
                                <div class="form-group">
                                    <label for="slide_desc">Description</label>
                                    <textarea name="description" id="slide_desc" rows="2" class="form-control" placeholder="Short caption overlaid on the slide..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Slide Image <span class="error_red">*</span></label>
                                    <div id="featuredImagePreviewBox" style="margin-bottom:8px;"></div>
                                    <input type="hidden" name="selected_media_path" id="selected_media_path" value="">
                                    <button type="button" class="btn btn-default btn-sm btn-block" style="margin-bottom:8px;" onclick="openMediaPicker('featured')">
                                        <i class="fa fa-picture-o"></i> Choose from Media Library
                                    </button>
                                    <input type="file" name="slide_image" class="form-control" accept="image/*">
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="button_text">Button Label</label>
                                            <input type="text" name="button_text" id="button_text" class="form-control" placeholder="Read More">
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="button_link">Button Link URL</label>
                                            <input type="text" name="button_link" id="button_link" class="form-control" placeholder="/page/about-us or https://...">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ordering">Order</label>
                                    <input type="number" name="ordering" id="ordering" class="form-control" value="{{ $banner->slides->count() + 1 }}">
                                </div>
                                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add Slide</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="{{ isset($viewmode) ? 'col-md-12' : 'col-md-7' }}">
                    <div class="x_panel">
                        <div class="x_title">
                            <h3>
                                Slides ({{ $banner->slides->count() }})
                                @if(!isset($viewmode) && $banner->slides->count() > 1)
                                    <small style="margin-left:8px; color:#64748b;"><i class="fa fa-arrows-v"></i> Drag rows to reorder</small>
                                @endif
                                <span id="slideReorderStatus" class="label label-success" style="display:none; margin-left:10px; font-size:11px;">Order saved!</span>
                            </h3>
                            <div class="clearfix"></div>
                        </div>
                        @if($banner->slides->isEmpty())
                            <p class="text-muted">No slides yet. Add your first slide on the left.</p>
                        @else
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    @if(!isset($viewmode))
                                        <th width="34"></th>
                                    @endif
                                    <th width="50">#</th>
                                    <th width="90">Image</th>
                                    <th>Title &amp; Description</th>
                                    <th>Link</th>
                                    @if(!isset($viewmode))
                                        <th width="60">Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody id="bannerSlidesSortable">
                                @foreach($banner->slides as $slide)
                                    <tr data-id="{{ $slide->id }}" @if(!isset($viewmode)) draggable="true" style="cursor:move;" @endif>
                                        @if(!isset($viewmode))
                                            <td class="text-center text-muted"><i class="fa fa-bars"></i></td>
                                        @endif
                                        <td class="slide-order-num">{{ $slide->ordering }}</td>
                                        <td>
                                            <img src="{{ str_starts_with($slide->image, 'http') ? $slide->image : asset($slide->image) }}" alt="" style="width:76px; height:48px; object-fit:cover; border-radius:4px;">
                                        </td>
                                        <td>
                                            <strong>{{ $slide->title ?: '—' }}</strong><br>
                                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($slide->description, 80) }}</small>
                                        </td>
                                        <td>
                                            @if($slide->button_link)
                                                <span class="label label-info">{{ $slide->button_text ?: 'Read More' }}</span><br>
                                                <small><code>{{ $slide->button_link }}</code></small>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        @if(!isset($viewmode))
                                            <td>
                                                <form action="{{ route('extensionsvalley.cms.deletebannerslide', ['id' => $slide->id]) }}" method="POST" onsubmit="return confirm('Delete this slide?');">
                                                    @csrf
                                                    <input type="hidden" name="accesstoken" value="{{ $token }}"/>
                                                    <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            <script>
                (function () {
                    var tbody = document.getElementById('bannerSlidesSortable');
                    if (!tbody) return;
                    var dragged = null;
                    tbody.addEventListener('dragstart', function (e) {
                        dragged = e.target.closest('tr');
                        if (dragged) dragged.style.opacity = '0.45';
                    });
                    tbody.addEventListener('dragover', function (e) {
                        e.preventDefault();
                        var target = e.target.closest('tr');
                        if (!target || !dragged || target === dragged) return;
                        var rect = target.getBoundingClientRect();
                        tbody.insertBefore(dragged, (e.clientY - rect.top) / rect.height > 0.5 ? target.nextSibling : target);
                    });
                    tbody.addEventListener('dragend', function () {
                        if (dragged) { dragged.style.opacity = ''; dragged = null; }
                        var order = [];
                        tbody.querySelectorAll('tr[data-id]').forEach(function (row, idx) {
                            order.push(row.getAttribute('data-id'));
                            var cell = row.querySelector('.slide-order-num');
                            if (cell) cell.textContent = idx + 1;
                        });
                        if (order.length > 0) {
                            jQuery.post('{{ route("extensionsvalley.cms.reorderbannerslides") }}', {
                                _token: '{{ csrf_token() }}',
                                order: order
                            }, function () {
                                var badge = document.getElementById('slideReorderStatus');
                                if (badge) { badge.style.display = 'inline-block'; setTimeout(function () { badge.style.display = 'none'; }, 2000); }
                            });
                        }
                    });
                })();
            </script>
        @endif
    </div>
    @include('Cms::admin.partials.mediapicker')
@stop

