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
            $action = isset($menu) ? 'extensionsvalley.cms.updatemenu' : 'extensionsvalley.cms.savemenu';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
            $token = request()->get('accesstoken', base64_encode('extensionsvalley.cms.menus'));
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Menu Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $menu->name ?? '') }}" placeholder="Main Navigation" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug', $menu->slug ?? '') }}" placeholder="main-nav" {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('slug') }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        @include('Cms::admin.partials.language_select', ['currentLanguageCode' => $menu->language_code ?? null])
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="location">Theme Location <span class="error_red">*</span></label>
                            <select name="location" id="location" class="form-control" required {{ $disabled }}>
                                @foreach(['header' => 'Header Navigation', 'footer' => 'Footer Navigation', 'sidebar' => 'Sidebar Navigation'] as $locKey => $locLabel)
                                    <option value="{{ $locKey }}" {{ old('location', $menu->location ?? 'header') == $locKey ? 'selected' : '' }}>{{ $locLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                <option value="1" {{ old('status', $menu->status ?? 1) == 1 ? 'selected' : '' }}>Publish</option>
                                <option value="0" {{ old('status', $menu->status ?? 1) == 0 ? 'selected' : '' }}>Unpublish</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if(isset($menu->id))
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ $token }}"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_menus') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">{{ isset($menu) ? 'Update Menu' : 'Create Menu & Add Items' }}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if(isset($menu))
            <div class="row">
                @if(!isset($viewmode))
                    <div class="col-md-5">
                        <div class="x_panel">
                            <div class="x_title">
                                <h3>Add Menu Item</h3>
                                <div class="clearfix"></div>
                            </div>
                            <form action="{{ route('extensionsvalley.cms.savemenuitem') }}" method="POST">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->id }}"/>
                                <input type="hidden" name="accesstoken" value="{{ $token }}"/>

                                <div class="form-group">
                                    <label for="item_title">Link Label <span class="error_red">*</span></label>
                                    <input type="text" name="title" id="item_title" class="form-control" required placeholder="e.g. About Us">
                                </div>

                                <div class="form-group">
                                    <label for="item_type">Link Type</label>
                                    <select name="type" id="item_type" class="form-control" onchange="toggleMenuItemTarget()">
                                        <option value="custom">Custom URL</option>
                                        <option value="page">CMS Page</option>
                                        <option value="post">CMS Post</option>
                                        <option value="category">CMS Category</option>
                                    </select>
                                </div>

                                <div class="form-group item-target-group" id="target_custom">
                                    <label for="item_url">URL</label>
                                    <input type="text" name="url" id="item_url" class="form-control" placeholder="/cms or https://example.com">
                                </div>

                                <div class="form-group item-target-group" id="target_page" style="display:none;">
                                    <label for="page_id">Select Page</label>
                                    <select name="page_id" id="page_id" class="form-control">
                                        @foreach($pages as $pid => $ptitle)
                                            <option value="{{ $pid }}">{{ $ptitle }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group item-target-group" id="target_post" style="display:none;">
                                    <label for="post_id">Select Post</label>
                                    <select name="post_id" id="post_id" class="form-control">
                                        @foreach($posts as $pid => $ptitle)
                                            <option value="{{ $pid }}">{{ $ptitle }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group item-target-group" id="target_category" style="display:none;">
                                    <label for="category_id">Select Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        @foreach($categories as $cid => $cname)
                                            <option value="{{ $cid }}">{{ $cname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="parent_id">Parent Item</label>
                                            <select name="parent_id" id="parent_id" class="form-control">
                                                <option value="">— Top Level —</option>
                                                @foreach($menu->items as $existing)
                                                    <option value="{{ $existing->id }}">{{ $existing->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ordering">Order</label>
                                            <input type="number" name="ordering" id="ordering" class="form-control" value="{{ $menu->items->count() + 1 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="target">Open In</label>
                                            <select name="target" id="target" class="form-control">
                                                <option value="_self">Same Tab</option>
                                                <option value="_blank">New Tab</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add to Menu</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="{{ isset($viewmode) ? 'col-md-12' : 'col-md-7' }}">
                    <div class="x_panel">
                        <div class="x_title">
                            <h3>
                                Menu Items ({{ $menu->items->count() }})
                                @if(!isset($viewmode) && $menu->items->count() > 1)
                                    <small style="margin-left:8px; color:#64748b;"><i class="fa fa-arrows-v"></i> Drag rows to reorder</small>
                                @endif
                                <span id="menuReorderStatus" class="label label-success" style="display:none; margin-left:10px; font-size:11px;">Order saved!</span>
                            </h3>
                            <div class="clearfix"></div>
                        </div>
                        @if($menu->items->isEmpty())
                            <p class="text-muted">No menu items yet. Use the form on the left to add links.</p>
                        @else
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    @if(!isset($viewmode))
                                        <th width="36"></th>
                                    @endif
                                    <th width="60">Order</th>
                                    <th>Label</th>
                                    <th>Type</th>
                                    <th>Parent</th>
                                    <th>Target URL</th>
                                    @if(!isset($viewmode))
                                        <th width="80">Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody id="menuItemsSortable">
                                @foreach($menu->items as $item)
                                    <tr data-id="{{ $item->id }}" @if(!isset($viewmode)) draggable="true" style="cursor:move;" @endif>
                                        @if(!isset($viewmode))
                                            <td class="text-center text-muted" title="Drag to reorder"><i class="fa fa-bars"></i></td>
                                        @endif
                                        <td class="menu-order-num">{{ $item->ordering }}</td>
                                        <td><strong>{{ $item->title }}</strong></td>
                                        <td><span class="label label-info">{{ ucfirst($item->type) }}</span></td>
                                        <td>{{ $item->parent->title ?? '—' }}</td>
                                        <td><code>{{ $item->resolved_url }}</code></td>
                                        @if(!isset($viewmode))
                                            <td>
                                                <form action="{{ route('extensionsvalley.cms.deletemenuitem', ['id' => $item->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Remove this menu item?');">
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
                function toggleMenuItemTarget() {
                    var type = document.getElementById('item_type').value;
                    document.querySelectorAll('.item-target-group').forEach(function (el) {
                        el.style.display = 'none';
                    });
                    var active = document.getElementById('target_' + type);
                    if (active) active.style.display = 'block';
                }

                (function () {
                    var tbody = document.getElementById('menuItemsSortable');
                    if (!tbody) return;
                    var draggedRow = null;

                    tbody.addEventListener('dragstart', function (e) {
                        draggedRow = e.target.closest('tr');
                        if (!draggedRow) return;
                        draggedRow.style.opacity = '0.45';
                        e.dataTransfer.effectAllowed = 'move';
                    });

                    tbody.addEventListener('dragover', function (e) {
                        e.preventDefault();
                        var targetRow = e.target.closest('tr');
                        if (!targetRow || !draggedRow || targetRow === draggedRow) return;
                        var rect = targetRow.getBoundingClientRect();
                        var next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
                        tbody.insertBefore(draggedRow, next ? targetRow.nextSibling : targetRow);
                    });

                    tbody.addEventListener('dragend', function () {
                        if (draggedRow) {
                            draggedRow.style.opacity = '';
                            draggedRow = null;
                        }
                        var order = [];
                        tbody.querySelectorAll('tr[data-id]').forEach(function (row, idx) {
                            order.push(row.getAttribute('data-id'));
                            var cell = row.querySelector('.menu-order-num');
                            if (cell) cell.textContent = idx + 1;
                        });
                        if (order.length > 0) {
                            jQuery.post('{{ route("extensionsvalley.cms.reordermenuitems") }}', {
                                _token: '{{ csrf_token() }}',
                                order: order
                            }, function () {
                                var badge = document.getElementById('menuReorderStatus');
                                if (badge) {
                                    badge.style.display = 'inline-block';
                                    setTimeout(function () { badge.style.display = 'none'; }, 2200);
                                }
                            });
                        }
                    });
                })();
            </script>
        @endif
    </div>
@stop

