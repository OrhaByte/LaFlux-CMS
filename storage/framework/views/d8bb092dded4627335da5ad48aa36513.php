<?php $__env->startSection('content'); ?>
    <div class="card">
        <h1 style="margin:0 0 8px 0;">Category: <?php echo e($category->name); ?></h1>
        <?php if($category->description): ?>
            <p class="meta" style="margin:0;"><?php echo e($category->description); ?></p>
        <?php endif; ?>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <article class="card">
            <h2 style="margin-top:0;">
                <a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug)); ?>"><?php echo e($post->title); ?></a>
            </h2>
            <div class="meta">Published <?php echo e(($post->published_at ?? $post->created_at)->format('M j, Y')); ?></div>
            <p><?php echo e($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 180)); ?></p>
            <a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug)); ?>">Read more &rarr;</a>
        </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card"><p>No posts in this category yet.</p></div>
    <?php endif; ?>

    <div><?php echo e($posts->links()); ?></div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Cms::frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/frontend/category.blade.php ENDPATH**/ ?>