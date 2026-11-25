<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title); ?> | <?php echo e(\WebConf::get('site_name')); ?></title>
    <?php if(\WebConf::get('fav_icon') != ""): ?>
        <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/<?php echo e(\WebConf::get('fav_icon')); ?>"/>
    <?php endif; ?>

    <!-- Bootstrap -->
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/bootstrap.min.css")); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/font-awesome.min.css")); ?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/custom.min.css")); ?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/core-admin.css")); ?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/green.css")); ?>" rel="stylesheet">
    <!-- Datatables -->
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/datatables/dataTables.bootstrap.min.css")); ?>"
          rel="stylesheet">
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/datatables/buttons.bootstrap.min.css")); ?>"
          rel="stylesheet">
    <!-- Select2 -->
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/select2.min.css")); ?>" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo e(asset("packages/extensionsvalley/dashboard/css/bootstrap-progressbar-3.3.4.min.css")); ?>" rel="stylesheet">

    <!-- jQuery -->
    <script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/jquery.min.js")); ?>"></script>
    <!-- Bootstrap -->
    <script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/bootstrap.min.js")); ?>"></script>

</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
    <?php echo $__env->yieldContent('content-header'); ?>
    <?php echo $__env->yieldContent('content-area'); ?>
    <!-- Footer Starts-->
    <?php echo $__env->make('Dashboard::dashboard.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Footer Ends-->
    </div>
</div>

<!-- iCheck -->
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/icheck.min.js")); ?>"></script>
<!-- Datatables -->
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/jquery.dataTables.min.js")); ?>"></script>
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/dataTables.bootstrap.min.js")); ?>"></script>
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/dataTables.buttons.min.js")); ?>"></script>
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/buttons.bootstrap.min.js")); ?>"></script>
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/buttons.flash.min.js")); ?>"></script>
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/buttons.html5.min.js")); ?>"></script>
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/buttons.print.min.js")); ?>"></script>
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/dataTables.responsive.min.js")); ?>"></script>
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/datatables/responsive.bootstrap.js")); ?>"></script>
<!-- select 2 JavaScript -->
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/select2.full.min.js")); ?>"></script>
<!-- Custom Core JavaScript -->
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/core-admin.js")); ?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/custom.min.js")); ?>"></script>
<?php if (! (View::hasSection('use_cms_editor'))): ?>
<!-- Tiny Mce Editor JavaScript -->
<script src="<?php echo e(asset("packages/extensionsvalley/dashboard/js/tinymce/tinymce.min.js")); ?>"></script>
<script>tinymce.init({
            selector: 'textarea.texteditor', theme: "modern",
            subfolder: "",
            height : "400",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor filemanager"
            ],
            image_advtab: true,
            content_css: '<?php echo e(asset("packages/extensionsvalley/dashboard/js/tinymce/skins/lightgray/content.min.css")); ?>',
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
            style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ],
        });</script>
<?php endif; ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".select2").select2();
    })
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/dashboard.blade.php ENDPATH**/ ?>