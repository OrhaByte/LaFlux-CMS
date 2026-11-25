<?php
                  $topnavigation = new Illuminate\Support\Collection;
                  \Event::dispatch('admin.topnavigation.groups', [$topnavigation]);
                  $topnavigation_items = [];
                  foreach($topnavigation as $items){
                   $topnavigation_items = array_merge($topnavigation_items,$items['items']);
                  }
                  usort($topnavigation_items, function($a, $b) {
                        return $a['order'] - $b['order'];
                    });

?>

          <?php if(sizeof($topnavigation_items)): ?>
            <?php $__currentLoopData = $topnavigation_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if ($__env->exists($key['layout'],['title' => $key['title']])) echo $__env->make($key['layout'],['title' => $key['title']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/partials/topnavbuilder.blade.php ENDPATH**/ ?>