<?php $__env->startSection('content'); ?>
    <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <article class="card">
            <?php if($post->featured_image): ?>
                <a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug)); ?>">
                    <img class="featured" src="<?php echo e(asset($post->featured_image)); ?>" alt="<?php echo e($post->title); ?>">
                </a>
            <?php endif; ?>
            <h2 style="margin-top:0;">
                <a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug)); ?>"><?php echo e($post->title); ?></a>
            </h2>
            <div class="meta">
                Published <?php echo e(($post->published_at ?? $post->created_at)->format('M j, Y')); ?>

                <?php if($post->category): ?>
                    &middot; in <a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $post->category->slug)); ?>"><?php echo e($post->category->name); ?></a>
                <?php endif; ?>
                <?php if($post->author): ?>
                    &middot; by <?php echo e($post->author->name); ?>

                <?php endif; ?>
            </div>
            <p><?php echo e($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 180)); ?></p>
            <?php if($post->tags->isNotEmpty()): ?>
                <div style="margin-bottom:10px;">
                    <?php $__currentLoopData = $post->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a class="badge" href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('tag/' . $tag->slug)); ?>">#<?php echo e($tag->name); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug)); ?>">Read more &rarr;</a>
        </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card">
            <p>No published posts yet.</p>
        </div>
    <?php endif; ?>

    <div><?php echo e($posts->links()); ?></div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Cms::frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/frontend/home.blade.php ENDPATH**/ ?>