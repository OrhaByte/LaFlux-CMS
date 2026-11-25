<?php $__env->startSection('content-header'); ?>
    <?php echo $__env->make('Dashboard::dashboard.partials.headersidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-area'); ?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2><?php echo e($title); ?> <small>Front-end multilingual URLs activate automatically when 2+ languages are active</small></h2>
                </div>
            </div>
        </div>
        <?php
            $action = isset($language) ? 'extensionsvalley.cms.updatelanguage' : 'extensionsvalley.cms.savelanguage';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
        ?>
        <div class="x_panel">
            <form action="<?php echo e(route($action)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group <?php echo e($errors->has('name') ? 'has-error' : ''); ?>">
                            <label for="name">Language Name <span class="error_red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="<?php echo e(old('name', $language->name ?? '')); ?>" placeholder="e.g. Arabic, Spanish, French" required <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('name')); ?></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group <?php echo e($errors->has('code') ? 'has-error' : ''); ?>">
                            <label for="code">2-Letter Code <span class="error_red">*</span></label>
                            <input type="text" name="code" id="code" maxlength="2" class="form-control"
                                   value="<?php echo e(old('code', $language->code ?? '')); ?>" placeholder="en, ar, es" required <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('code')); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="direction">Text Direction <span class="error_red">*</span></label>
                            <select name="direction" id="direction" class="form-control" required <?php echo e($disabled); ?>>
                                <option value="ltr" <?php echo e(old('direction', $language->direction ?? 'ltr') == 'ltr' ? 'selected' : ''); ?>>LTR (Left to Right)</option>
                                <option value="rtl" <?php echo e(old('direction', $language->direction ?? 'ltr') == 'rtl' ? 'selected' : ''); ?>>RTL (Right to Left)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status <span class="error_red">*</span></label>
                            <select name="status" id="status" class="form-control" required <?php echo e($disabled); ?>>
                                <option value="1" <?php echo e(old('status', $language->status ?? 1) == 1 ? 'selected' : ''); ?>>Active</option>
                                <option value="0" <?php echo e(old('status', $language->status ?? 1) == 0 ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_default" value="1"
                                       <?php echo e(old('is_default', $language->is_default ?? false) ? 'checked' : ''); ?> <?php echo e($disabled); ?>>
                                <strong>Set as Default Language</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <?php if(isset($language->id)): ?>
                    <input type="hidden" name="language_id" value="<?php echo e($language->id); ?>"/>
                <?php endif; ?>
                <input type="hidden" name="accesstoken" value="<?php echo e(request()->get('accesstoken', '')); ?>"/>

                <div class="row" style="margin-top:12px;">
                    <div class="col-md-12">
                        <a href="<?php echo e(url('admin/ExtensionsValley/cms/list/cms_languages')); ?>" class="btn btn-default">Cancel</a>
                        <?php if(!isset($viewmode)): ?>
                            <button type="submit" class="btn btn-primary">Save Language</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/admin/language/form.blade.php ENDPATH**/ ?>