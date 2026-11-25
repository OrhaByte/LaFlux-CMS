<?php $__env->startSection('content-header'); ?>

    <!-- Navigation Starts-->
    <?php echo $__env->make('Dashboard::dashboard.partials.headersidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Navigation Ends-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-area'); ?>

 <!-- page content -->
        <div class="right_col"  role="main">
        <div class="row">

          <?php
                  $widgets = new Illuminate\Support\Collection;
                  \Event::dispatch('admin.widgets.groups', [$widgets]);
                  $widget_items = [];
                  foreach($widgets as $items){
                   $widget_items = array_merge($widget_items,$items['items']);
                  }
                  usort($widget_items, function($a, $b) {
                        return $a['order'] - $b['order'];
                    });

          ?>

          <?php if(sizeof($widget_items)): ?>
            <?php $__currentLoopData = $widget_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if ($__env->exists($key['layout'],['layout_col' => $key['col']])) echo $__env->make($key['layout'],['layout_col' => $key['col']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>

        </div>
    </div>

    <!-- /page content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/index.blade.php ENDPATH**/ ?>