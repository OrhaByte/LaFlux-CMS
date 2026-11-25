<?php $__env->startSection('content-header'); ?>
    <?php echo $__env->make('Dashboard::dashboard.partials.headersidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-area'); ?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2>
                        <?php echo e($title); ?>

                        <?php if(isset($form)): ?>
                            <small style="margin-left:12px;">
                                Shortcode:
                                <code id="formShortcode">[form slug="<?php echo e($form->slug); ?>"]</code>
                                <button type="button" class="btn btn-xs btn-default" onclick="navigator.clipboard && navigator.clipboard.writeText(document.getElementById('formShortcode').textContent); alert('Shortcode copied!');">Copy</button>
                                <a href="<?php echo e(route('extensionsvalley.cms.formsubmissions', ['id' => $form->id])); ?>" class="btn btn-xs btn-info" style="margin-left:6px;"><i class="fa fa-inbox"></i> View Submissions</a>
                            </small>
                        <?php endif; ?>
                    </h2>
                </div>
            </div>
        </div>
        <?php
            $action = isset($form) ? 'extensionsvalley.cms.updateform' : 'extensionsvalley.cms.saveform';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
            $token = request()->get('accesstoken', base64_encode('extensionsvalley.cms.forms'));
        ?>
        <div class="x_panel">
            <form action="<?php echo e(route($action)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group <?php echo e($errors->has('name') ? 'has-error' : ''); ?>">
                            <label for="name">Form Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="<?php echo e(old('name', $form->name ?? '')); ?>" placeholder="Contact Us" required <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('name')); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group <?php echo e($errors->has('slug') ? 'has-error' : ''); ?>">
                            <label for="slug">Slug <small class="text-muted">(used in shortcode)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="<?php echo e(old('slug', $form->slug ?? '')); ?>" placeholder="contact-us" <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('slug')); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required <?php echo e($disabled); ?>>
                                <option value="1" <?php echo e(old('status', $form->status ?? 1) == 1 ? 'selected' : ''); ?>>Publish</option>
                                <option value="0" <?php echo e(old('status', $form->status ?? 1) == 0 ? 'selected' : ''); ?>>Unpublish</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="submit_label">Submit Button Label</label>
                            <input type="text" name="submit_label" id="submit_label" class="form-control"
                                   value="<?php echo e(old('submit_label', $form->submit_label ?? 'Send Message')); ?>" <?php echo e($readonly); ?>>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="success_message">Success Message</label>
                            <input type="text" name="success_message" id="success_message" class="form-control"
                                   value="<?php echo e(old('success_message', $form->success_message ?? 'Thank you! Your submission has been received.')); ?>" <?php echo e($readonly); ?>>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group <?php echo e($errors->has('notify_email') ? 'has-error' : ''); ?>">
                            <label for="notify_email">Notification Email <small class="text-muted">(optional)</small></label>
                            <input type="email" name="notify_email" id="notify_email" class="form-control"
                                   value="<?php echo e(old('notify_email', $form->notify_email ?? '')); ?>" placeholder="admin@example.com" <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('notify_email')); ?></span>
                        </div>
                    </div>
                </div>

                <?php if(isset($form->id)): ?>
                    <input type="hidden" name="form_id" value="<?php echo e($form->id); ?>"/>
                <?php endif; ?>
                <input type="hidden" name="accesstoken" value="<?php echo e($token); ?>"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="<?php echo e(url('admin/ExtensionsValley/cms/list/cms_forms')); ?>" class="btn btn-default">Cancel</a>
                        <?php if(!isset($viewmode)): ?>
                            <button type="submit" class="btn btn-primary"><?php echo e(isset($form) ? 'Update Form' : 'Create Form & Add Fields'); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <?php if(isset($form)): ?>
            <div class="row">
                <?php if(!isset($viewmode)): ?>
                    <div class="col-md-5">
                        <div class="x_panel">
                            <div class="x_title">
                                <h3>Add Form Field</h3>
                                <div class="clearfix"></div>
                            </div>
                            <form action="<?php echo e(route('extensionsvalley.cms.saveformfield')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="form_id" value="<?php echo e($form->id); ?>"/>
                                <input type="hidden" name="accesstoken" value="<?php echo e($token); ?>"/>

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
                                            <input type="number" name="ordering" id="field_ordering" class="form-control" value="<?php echo e($form->fields->count() + 1); ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add Field</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="<?php echo e(isset($viewmode) ? 'col-md-12' : 'col-md-7'); ?>">
                    <div class="x_panel">
                        <div class="x_title">
                            <h3>
                                Configured Fields (<?php echo e($form->fields->count()); ?>)
                                <?php if(!isset($viewmode) && $form->fields->count() > 1): ?>
                                    <small style="margin-left:8px; color:#64748b;"><i class="fa fa-arrows-v"></i> Drag rows to reorder</small>
                                <?php endif; ?>
                                <span id="fieldReorderStatus" class="label label-success" style="display:none; margin-left:10px; font-size:11px;">Order saved!</span>
                            </h3>
                            <div class="clearfix"></div>
                        </div>
                        <?php if($form->fields->isEmpty()): ?>
                            <p class="text-muted">No fields configured yet. Add fields on the left.</p>
                        <?php else: ?>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <?php if(!isset($viewmode)): ?>
                                        <th width="34"></th>
                                    <?php endif; ?>
                                    <th width="50">#</th>
                                    <th>Label</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <?php if(!isset($viewmode)): ?>
                                        <th width="60">Action</th>
                                    <?php endif; ?>
                                </tr>
                                </thead>
                                <tbody id="formFieldsSortable">
                                <?php $__currentLoopData = $form->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($field->id); ?>" <?php if(!isset($viewmode)): ?> draggable="true" style="cursor:move;" <?php endif; ?>>
                                        <?php if(!isset($viewmode)): ?>
                                            <td class="text-center text-muted"><i class="fa fa-bars"></i></td>
                                        <?php endif; ?>
                                        <td class="field-order-num"><?php echo e($field->ordering); ?></td>
                                        <td>
                                            <strong><?php echo e($field->label); ?></strong>
                                            <?php if($field->options): ?>
                                                <br><small class="text-muted">Options: <?php echo e($field->options); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><code><?php echo e($field->field_name); ?></code></td>
                                        <td><span class="label label-info"><?php echo e($field->field_type); ?></span></td>
                                        <td><?php echo $field->is_required ? '<span class="label label-danger">Yes</span>' : '<span class="label label-default">No</span>'; ?></td>
                                        <?php if(!isset($viewmode)): ?>
                                            <td>
                                                <form action="<?php echo e(route('extensionsvalley.cms.deleteformfield', ['id' => $field->id])); ?>" method="POST" onsubmit="return confirm('Delete this field?');">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="accesstoken" value="<?php echo e($token); ?>"/>
                                                    <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
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
                            jQuery.post('<?php echo e(route("extensionsvalley.cms.reorderformfields")); ?>', {
                                _token: '<?php echo e(csrf_token()); ?>',
                                order: order
                            }, function () {
                                var badge = document.getElementById('fieldReorderStatus');
                                if (badge) { badge.style.display = 'inline-block'; setTimeout(function () { badge.style.display = 'none'; }, 2000); }
                            });
                        }
                    });
                })();
            </script>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/admin/form/form.blade.php ENDPATH**/ ?>