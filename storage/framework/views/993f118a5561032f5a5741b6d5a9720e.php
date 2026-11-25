<div class="col-md-8 col-md-offset-3">
    <?php if(Session::has('message')): ?>
        <div class="message alert alert-success  col-md-12 text-center"><?php echo Session::get('message'); ?></div>
    <?php endif; ?>
    <?php if(Session::has('error')): ?>
        <div class="message alert alert-danger  col-md-12 text-center"><?php echo Session::get('error'); ?></div>
    <?php endif; ?>
    <?php if(Session::has('warning')): ?>
        <div class="message alert alert-warning  col-md-12 text-center"><?php echo Session::get('warning'); ?></div>
    <?php endif; ?>
</div>

<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/partials/statusmessage.blade.php ENDPATH**/ ?>