<div class="cms-form-widget" id="cms-form-<?php echo e($form->slug); ?>" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:24px; margin:22px 0;">
    <h3 style="margin-top:0; margin-bottom:14px;"><?php echo e($form->name); ?></h3>

    <?php if(session('cms_form_success_' . $form->slug)): ?>
        <div style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; padding:12px 14px; border-radius:6px; margin-bottom:14px;">
            <?php echo e(session('cms_form_success_' . $form->slug)); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->hasBag('cms_form_' . $form->slug)): ?>
        <div style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca; padding:12px 14px; border-radius:6px; margin-bottom:14px;">
            <?php $__currentLoopData = $errors->getBag('cms_form_' . $form->slug)->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($err); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('cms.frontend.form.submit', ['slug' => $form->slug])); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php $__currentLoopData = $form->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="margin-bottom:14px;">
                <label style="display:block; font-weight:600; margin-bottom:5px; font-size:0.92rem;">
                    <?php echo e($field->label); ?>

                    <?php if($field->is_required): ?> <span style="color:#dc2626;">*</span> <?php endif; ?>
                </label>

                <?php if($field->field_type === 'textarea'): ?>
                    <textarea name="<?php echo e($field->field_name); ?>" rows="4"
                              placeholder="<?php echo e($field->placeholder); ?>"
                              <?php echo e($field->is_required ? 'required' : ''); ?>

                              style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; font-family:inherit;"><?php echo e(old($field->field_name)); ?></textarea>
                <?php elseif($field->field_type === 'select'): ?>
                    <select name="<?php echo e($field->field_name); ?>" <?php echo e($field->is_required ? 'required' : ''); ?>

                            style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; background:#fff;">
                        <option value=""><?php echo e($field->placeholder ?: '— Select —'); ?></option>
                        <?php $__currentLoopData = $field->options_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($opt); ?>" <?php echo e(old($field->field_name) == $opt ? 'selected' : ''); ?>><?php echo e($opt); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php elseif($field->field_type === 'radio'): ?>
                    <div>
                        <?php $__currentLoopData = $field->options_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label style="margin-right:16px; font-weight:normal; cursor:pointer;">
                                <input type="radio" name="<?php echo e($field->field_name); ?>" value="<?php echo e($opt); ?>" <?php echo e(old($field->field_name) == $opt ? 'checked' : ''); ?> <?php echo e($field->is_required ? 'required' : ''); ?>>
                                <?php echo e($opt); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php elseif($field->field_type === 'checkbox'): ?>
                    <div>
                        <?php $oldVals = (array) old($field->field_name, []); ?>
                        <?php $__currentLoopData = $field->options_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label style="margin-right:16px; font-weight:normal; cursor:pointer;">
                                <input type="checkbox" name="<?php echo e($field->field_name); ?>[]" value="<?php echo e($opt); ?>" <?php echo e(in_array($opt, $oldVals) ? 'checked' : ''); ?>>
                                <?php echo e($opt); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <input type="<?php echo e($field->field_type); ?>" name="<?php echo e($field->field_name); ?>"
                           value="<?php echo e(old($field->field_name)); ?>"
                           placeholder="<?php echo e($field->placeholder); ?>"
                           <?php echo e($field->is_required ? 'required' : ''); ?>

                           style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <button type="submit" style="background:#2563eb; color:#fff; border:0; padding:10px 22px; border-radius:6px; font-weight:600; cursor:pointer;">
            <?php echo e($form->submit_label ?: 'Submit'); ?>

        </button>
    </form>
</div>

<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/frontend/shortcodes/form.blade.php ENDPATH**/ ?>