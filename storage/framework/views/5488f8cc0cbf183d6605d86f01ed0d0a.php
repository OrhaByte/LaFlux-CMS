<?php $__env->startSection('content-header'); ?>
    <?php echo $__env->make('Dashboard::dashboard.partials.headersidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-area'); ?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2><?php echo e($title); ?></h2>
                </div>
            </div>
        </div>
        <?php
            $action = isset($menu) ? 'extensionsvalley.cms.updatemenu' : 'extensionsvalley.cms.savemenu';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
            $token = request()->get('accesstoken', base64_encode('extensionsvalley.cms.menus'));
        ?>
        <div class="x_panel">
            <form action="<?php echo e(route($action)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group <?php echo e($errors->has('name') ? 'has-error' : ''); ?>">
                            <label for="name">Menu Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="<?php echo e(old('name', $menu->name ?? '')); ?>" placeholder="Main Navigation" required <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('name')); ?></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group <?php echo e($errors->has('slug') ? 'has-error' : ''); ?>">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="<?php echo e(old('slug', $menu->slug ?? '')); ?>" placeholder="main-nav" <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('slug')); ?></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <?php echo $__env->make('Cms::admin.partials.language_select', ['currentLanguageCode' => $menu->language_code ?? null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="location">Theme Location <span class="error_red">*</span></label>
                            <select name="location" id="location" class="form-control" required <?php echo e($disabled); ?>>
                                <?php $__currentLoopData = ['header' => 'Header Navigation', 'footer' => 'Footer Navigation', 'sidebar' => 'Sidebar Navigation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locKey => $locLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($locKey); ?>" <?php echo e(old('location', $menu->location ?? 'header') == $locKey ? 'selected' : ''); ?>><?php echo e($locLabel); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required <?php echo e($disabled); ?>>
                                <option value="1" <?php echo e(old('status', $menu->status ?? 1) == 1 ? 'selected' : ''); ?>>Publish</option>
                                <option value="0" <?php echo e(old('status', $menu->status ?? 1) == 0 ? 'selected' : ''); ?>>Unpublish</option>
                            </select>
                        </div>
                    </div>
                </div>

                <?php if(isset($menu->id)): ?>
                    <input type="hidden" name="menu_id" value="<?php echo e($menu->id); ?>"/>
                <?php endif; ?>
                <input type="hidden" name="accesstoken" value="<?php echo e($token); ?>"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="<?php echo e(url('admin/ExtensionsValley/cms/list/cms_menus')); ?>" class="btn btn-default">Cancel</a>
                        <?php if(!isset($viewmode)): ?>
                            <button type="submit" class="btn btn-primary"><?php echo e(isset($menu) ? 'Update Menu' : 'Create Menu & Add Items'); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <?php if(isset($menu)): ?>
            <div class="row">
                <?php if(!isset($viewmode)): ?>
                    <div class="col-md-5">
                        <div class="x_panel">
                            <div class="x_title">
                                <h3>Add Menu Item</h3>
                                <div class="clearfix"></div>
                            </div>
                            <form action="<?php echo e(route('extensionsvalley.cms.savemenuitem')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="menu_id" value="<?php echo e($menu->id); ?>"/>
                                <input type="hidden" name="accesstoken" value="<?php echo e($token); ?>"/>

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
                                        <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pid => $ptitle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pid); ?>"><?php echo e($ptitle); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="form-group item-target-group" id="target_post" style="display:none;">
                                    <label for="post_id">Select Post</label>
                                    <select name="post_id" id="post_id" class="form-control">
                                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pid => $ptitle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pid); ?>"><?php echo e($ptitle); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="form-group item-target-group" id="target_category" style="display:none;">
                                    <label for="category_id">Select Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cid => $cname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($cid); ?>"><?php echo e($cname); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="parent_id">Parent Item</label>
                                            <select name="parent_id" id="parent_id" class="form-control">
                                                <option value="">— Top Level —</option>
                                                <?php $__currentLoopData = $menu->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($existing->id); ?>"><?php echo e($existing->title); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ordering">Order</label>
                                            <input type="number" name="ordering" id="ordering" class="form-control" value="<?php echo e($menu->items->count() + 1); ?>">
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
                <?php endif; ?>

                <div class="<?php echo e(isset($viewmode) ? 'col-md-12' : 'col-md-7'); ?>">
                    <div class="x_panel">
                        <div class="x_title">
                            <h3>
                                Menu Items (<?php echo e($menu->items->count()); ?>)
                                <?php if(!isset($viewmode) && $menu->items->count() > 1): ?>
                                    <small style="margin-left:8px; color:#64748b;"><i class="fa fa-arrows-v"></i> Drag rows to reorder</small>
                                <?php endif; ?>
                                <span id="menuReorderStatus" class="label label-success" style="display:none; margin-left:10px; font-size:11px;">Order saved!</span>
                            </h3>
                            <div class="clearfix"></div>
                        </div>
                        <?php if($menu->items->isEmpty()): ?>
                            <p class="text-muted">No menu items yet. Use the form on the left to add links.</p>
                        <?php else: ?>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <?php if(!isset($viewmode)): ?>
                                        <th width="36"></th>
                                    <?php endif; ?>
                                    <th width="60">Order</th>
                                    <th>Label</th>
                                    <th>Type</th>
                                    <th>Parent</th>
                                    <th>Target URL</th>
                                    <?php if(!isset($viewmode)): ?>
                                        <th width="80">Action</th>
                                    <?php endif; ?>
                                </tr>
                                </thead>
                                <tbody id="menuItemsSortable">
                                <?php $__currentLoopData = $menu->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($item->id); ?>" <?php if(!isset($viewmode)): ?> draggable="true" style="cursor:move;" <?php endif; ?>>
                                        <?php if(!isset($viewmode)): ?>
                                            <td class="text-center text-muted" title="Drag to reorder"><i class="fa fa-bars"></i></td>
                                        <?php endif; ?>
                                        <td class="menu-order-num"><?php echo e($item->ordering); ?></td>
                                        <td><strong><?php echo e($item->title); ?></strong></td>
                                        <td><span class="label label-info"><?php echo e(ucfirst($item->type)); ?></span></td>
                                        <td><?php echo e($item->parent->title ?? '—'); ?></td>
                                        <td><code><?php echo e($item->resolved_url); ?></code></td>
                                        <?php if(!isset($viewmode)): ?>
                                            <td>
                                                <form action="<?php echo e(route('extensionsvalley.cms.deletemenuitem', ['id' => $item->id])); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Remove this menu item?');">
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
                            jQuery.post('<?php echo e(route("extensionsvalley.cms.reordermenuitems")); ?>', {
                                _token: '<?php echo e(csrf_token()); ?>',
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
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/admin/menu/form.blade.php ENDPATH**/ ?>