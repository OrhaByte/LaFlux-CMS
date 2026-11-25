<?php $__env->startSection('content'); ?>
    <article class="card">
        <?php if($page->featured_image): ?>
            <img class="featured" src="<?php echo e(asset($page->featured_image)); ?>" alt="<?php echo e($page->title); ?>">
        <?php endif; ?>
        <h1 style="margin-top:0;"><?php echo e($page->title); ?></h1>
        <div class="meta">
            Updated <?php echo e(($page->updated_at ?? $page->created_at)->format('M j, Y')); ?>

            <?php if($page->author): ?>
                &middot; by <?php echo e($page->author->name); ?>

            <?php endif; ?>
        </div>
        <div><?php echo \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render($page->content); ?></div>
    </article>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Cms::frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/frontend/page.blade.php ENDPATH**/ ?>