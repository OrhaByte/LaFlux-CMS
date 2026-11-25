<?php
    $cmsLanguages = \ExtensionsValley\Cms\Helpers\CmsHelper::activeLanguages();
    $selectedLang = old('language_code', $currentLanguageCode ?? \ExtensionsValley\Cms\Helpers\CmsHelper::defaultLanguageCode());
?>
<div class="form-group <?php echo e($errors->has('language_code') ? 'has-error' : ''); ?>">
    <label for="language_code">Language <span class="error_red">*</span></label>
    <select name="language_code" id="language_code" class="form-control" required <?php echo e($disabled ?? ''); ?>>
        <?php $__currentLoopData = $cmsLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $langItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($langItem->code); ?>" <?php echo e($selectedLang === $langItem->code ? 'selected' : ''); ?>>
                <?php echo e($langItem->name); ?> (<?php echo e(strtoupper($langItem->code)); ?>)
            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <span class="error_red"><?php echo e($errors->first('language_code')); ?></span>
</div>

<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/admin/partials/language_select.blade.php ENDPATH**/ ?>