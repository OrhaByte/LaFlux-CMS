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
                        @if(isset($form))
                            <small style="margin-left:12px;">
                                Shortcode:
                                <code id="formShortcode">[form slug="{{ $form->slug }}"]</code>
                                <button type="button" class="btn btn-xs btn-default" onclick="navigator.clipboard && navigator.clipboard.writeText(document.getElementById('formShortcode').textContent); alert('Shortcode copied!');">Copy</button>
                                <a href="{{ route('extensionsvalley.cms.formsubmissions', ['id' => $form->id]) }}" class="btn btn-xs btn-info" style="margin-left:6px;"><i class="fa fa-inbox"></i> View Submissions</a>
                            </small>
                        @endif
                    </h2>
                </div>
            </div>
        </div>
        @php
            $action = isset($form) ? 'extensionsvalley.cms.updateform' : 'extensionsvalley.cms.saveform';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
            $token = request()->get('accesstoken', base64_encode('extensionsvalley.cms.forms'));
        @endphp
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Form Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $form->name ?? '') }}" placeholder="Contact Us" required {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label for="slug">Slug <small class="text-muted">(used in shortcode)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug', $form->slug ?? '') }}" placeholder="contact-us" {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('slug') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required {{ $disabled }}>
                                <option value="1" {{ old('status', $form->status ?? 1) == 1 ? 'selected' : '' }}>Publish</option>
                                <option value="0" {{ old('status', $form->status ?? 1) == 0 ? 'selected' : '' }}>Unpublish</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="submit_label">Submit Button Label</label>
                            <input type="text" name="submit_label" id="submit_label" class="form-control"
                                   value="{{ old('submit_label', $form->submit_label ?? 'Send Message') }}" {{ $readonly }}>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="success_message">Success Message</label>
                            <input type="text" name="success_message" id="success_message" class="form-control"
                                   value="{{ old('success_message', $form->success_message ?? 'Thank you! Your submission has been received.') }}" {{ $readonly }}>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('notify_email') ? 'has-error' : '' }}">
                            <label for="notify_email">Notification Emails <small class="text-muted">(comma separated, optional)</small></label>
                            <input type="text" name="notify_email" id="notify_email" class="form-control"
                                   value="{{ old('notify_email', $form->notify_email ?? '') }}" placeholder="admin@example.com, sales@example.com" {{ $readonly }}>
                            <span class="error_red">{{ $errors->first('notify_email') }}</span>
                        </div>
                    </div>
                </div>

                @if(isset($form->id))
                    <input type="hidden" name="form_id" value="{{ $form->id }}"/>
                @endif
                <input type="hidden" name="accesstoken" value="{{ $token }}"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_forms') }}" class="btn btn-default">Cancel</a>
                        @if(!isset($viewmode))
                            <button type="submit" class="btn btn-primary">{{ isset($form) ? 'Update Form' : 'Create Form & Add Fields' }}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if(isset($form))
            <div class="row">
                @if(!isset($viewmode))
                    <div class="col-md-5">
                        <div class="x_panel">
                            <div class="x_title">
                                <h3>Add Form Field</h3>
                                <div class="clearfix"></div>
                            </div>
                            <form action="{{ route('extensionsvalley.cms.saveformfield') }}" method="POST">
                                @csrf
                                <input type="hidden" name="form_id" value="{{ $form->id }}"/>
                                <input type="hidden" name="accesstoken" value="{{ $token }}"/>

                                <div class="form-group">
                                    <label for="field_label">Field Label <span class="error_red">*</span></label>
                                    <input type="text" name="label" id="field_label" class="form-control" required placeholder="e.g. Full Name">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="field_name">Field Name <small class="text-muted">(auto if blank)</small></label>
                                            <input type="text" name="field_name" id="field_name" class="form-control" placeholder="full_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="field_type">Field Type</label>
                                            <select name="field_type" id="field_type" class="form-control" onchange="toggleFieldOptions()">
                                                <option value="text">Text</option>
                                                <option value="email">Email</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="number">Number</option>
                                                <option value="tel">Phone (Tel)</option>
                                                <option value="date">Date</option>
                                                <option value="select">Dropdown (Select)</option>
                                                <option value="radio">Radio Buttons</option>
                                                <option value="checkbox">Checkboxes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="placeholder">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control" placeholder="Enter your value...">
                                </div>
                                <div class="form-group" id="fieldOptionsGroup" style="display:none;">
                                    <label for="options">Options <small class="text-muted">(comma separated)</small></label>
                                    <input type="text" name="options" id="options" class="form-control" placeholder="Support, Sales, General Inquiry">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="checkbox" style="margin-top:25px;">
                                            <label><input type="checkbox" name="is_required" value="1"> Required Field</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="field_ordering">Order</label>
                                            <input type="number" name="ordering" id="field_ordering" class="form-control" value="{{ $form->fields->count() + 1 }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add Field</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="{{ isset($viewmode) ? 'col-md-12' : 'col-md-7' }}">
                    <div class="x_panel">
                        <div class="x_title">
                            <h3>
                                Configured Fields ({{ $form->fields->count() }})
                                @if(!isset($viewmode) && $form->fields->count() > 1)
                                    <small style="margin-left:8px; color:#64748b;"><i class="fa fa-arrows-v"></i> Drag rows to reorder</small>
                                @endif
                                <span id="fieldReorderStatus" class="label label-success" style="display:none; margin-left:10px; font-size:11px;">Order saved!</span>
                            </h3>
                            <div class="clearfix"></div>
                        </div>
                        @if($form->fields->isEmpty())
                            <p class="text-muted">No fields configured yet. Add fields on the left.</p>
                        @else
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    @if(!isset($viewmode))
                                        <th width="34"></th>
                                    @endif
                                    <th width="50">#</th>
                                    <th>Label</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    @if(!isset($viewmode))
                                        <th width="60">Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody id="formFieldsSortable">
                                @foreach($form->fields as $field)
                                    <tr data-id="{{ $field->id }}" @if(!isset($viewmode)) draggable="true" style="cursor:move;" @endif>
                                        @if(!isset($viewmode))
                                            <td class="text-center text-muted"><i class="fa fa-bars"></i></td>
                                        @endif
                                        <td class="field-order-num">{{ $field->ordering }}</td>
                                        <td>
                                            <strong>{{ $field->label }}</strong>
                                            @if($field->options)
                                                <br><small class="text-muted">Options: {{ $field->options }}</small>
                                            @endif
                                        </td>
                                        <td><code>{{ $field->field_name }}</code></td>
                                        <td><span class="label label-info">{{ $field->field_type }}</span></td>
                                        <td>{!! $field->is_required ? '<span class="label label-danger">Yes</span>' : '<span class="label label-default">No</span>' !!}</td>
                                        @if(!isset($viewmode))
                                            <td>
                                                <form action="{{ route('extensionsvalley.cms.deleteformfield', ['id' => $field->id]) }}" method="POST" onsubmit="return confirm('Delete this field?');">
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
                function toggleFieldOptions() {
                    var t = document.getElementById('field_type').value;
                    var grp = document.getElementById('fieldOptionsGroup');
                    if (grp) grp.style.display = (t === 'select' || t === 'radio' || t === 'checkbox') ? 'block' : 'none';
                }

                (function () {
                    var tbody = document.getElementById('formFieldsSortable');
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
                            var cell = row.querySelector('.field-order-num');
                            if (cell) cell.textContent = idx + 1;
                        });
                        if (order.length > 0) {
                            jQuery.post('{{ route("extensionsvalley.cms.reorderformfields") }}', {
                                _token: '{{ csrf_token() }}',
                                order: order
                            }, function () {
                                var badge = document.getElementById('fieldReorderStatus');
                                if (badge) { badge.style.display = 'inline-block'; setTimeout(function () { badge.style.display = 'none'; }, 2000); }
                            });
                        }
                    });
                })();
            </script>
        @endif
    </div>
@stop

