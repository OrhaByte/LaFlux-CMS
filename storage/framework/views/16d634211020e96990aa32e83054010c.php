<?php $__env->startSection('use_cms_editor', '1'); ?>
<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    jQuery(document).ready(function () {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: 'textarea.cms-editor',
                base_url: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3',
                suffix: '.min',
                promotion: false,
                branding: false,
                height: 400,
                menubar: true,
                plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat code fullscreen',
                relative_urls: false,
                remove_script_host: false,
                file_picker_types: 'image',
                file_picker_callback: function (callback, value, meta) {
                    if (meta.filetype === 'image' && typeof window.openMediaPicker === 'function') {
                        window.openMediaPicker('tinymce_cb', callback);
                    }
                },
                readonly: <?php echo e(isset($viewmode) ? 'true' : 'false'); ?>

            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/admin/partials/tinymce.blade.php ENDPATH**/ ?>