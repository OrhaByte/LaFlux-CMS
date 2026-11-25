<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo e(route('extensionsvalley.admin.dashboard')); ?>" class="site_title">
                <?php if(\WebConf::get('site_name') != ""): ?>
                    <i class="fa fa-paw"></i>
                    <span><?php echo e(\WebConf::get('site_name')); ?></span>
                <?php else: ?>
                    <i class="fa fa-paw"></i> <span>ExtensionsValley</span>
                <?php endif; ?>
            </a>
        </div>

        <div class="clearfix"></div>
        <?php echo $__env->make('Dashboard::dashboard.partials.avatarsection', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <br/>
        <!-- sidebar menu -->
    <?php echo $__env->make('Dashboard::dashboard.partials.sidemenu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
    <?php echo $__env->make('Dashboard::dashboard.partials.sidemenufooter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- /menu footer buttons -->
    </div>
</div>
<!-- top navigation -->
<?php echo $__env->make('Dashboard::dashboard.partials.topnavigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- /top navigation -->
<!-- Message Starts-->
<?php echo $__env->make('Dashboard::dashboard.partials.statusmessage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- Message Ends-->
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/partials/headersidebar.blade.php ENDPATH**/ ?>