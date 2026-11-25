<?php $__env->startSection('content'); ?>
    <article class="card">
        <?php if($post->featured_image): ?>
            <img class="featured" src="<?php echo e(asset($post->featured_image)); ?>" alt="<?php echo e($post->title); ?>">
        <?php endif; ?>
        <h1 style="margin-top:0;"><?php echo e($post->title); ?></h1>
        <div class="meta">
            Published <?php echo e(($post->published_at ?? $post->created_at)->format('M j, Y')); ?>

            <?php if($post->category): ?>
                &middot; in <a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $post->category->slug)); ?>"><?php echo e($post->category->name); ?></a>
            <?php endif; ?>
            <?php if($post->author): ?>
                &middot; by <?php echo e($post->author->name); ?>

            <?php endif; ?>
        </div>
        <div><?php echo \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render($post->content); ?></div>
        <?php if($post->tags->isNotEmpty()): ?>
            <hr style="margin:20px 0;border:0;border-top:1px solid #e2e8f0;">
            <div>
                <?php $__currentLoopData = $post->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a class="badge" href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('tag/' . $tag->slug)); ?>">#<?php echo e($tag->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </article>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Cms::frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/frontend/post.blade.php ENDPATH**/ ?>