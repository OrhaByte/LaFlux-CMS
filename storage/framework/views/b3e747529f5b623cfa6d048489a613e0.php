<?php
    $hasPosts = \Illuminate\Support\Facades\Schema::hasTable('cms_posts');
    $hasPages = \Illuminate\Support\Facades\Schema::hasTable('cms_pages');
    $hasCategories = \Illuminate\Support\Facades\Schema::hasTable('cms_categories');

    $recentPosts = $hasPosts
        ? \Illuminate\Support\Facades\DB::table('cms_posts as p')
            ->leftJoin('cms_categories as c', 'p.category_id', '=', 'c.id')
            ->select('p.id', 'p.title', 'p.slug', 'p.status', 'p.published_at', 'p.created_at', 'c.name as category_name')
            ->whereNull('p.deleted_at')
            ->orderByDesc('p.created_at')
            ->limit(6)
            ->get()
        : collect();

    $recentPages = $hasPages
        ? \Illuminate\Support\Facades\DB::table('cms_pages')
            ->select('id', 'title', 'slug', 'status', 'published_at', 'updated_at')
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get()
        : collect();

    $categoryStats = ($hasCategories && $hasPosts)
        ? \Illuminate\Support\Facades\DB::table('cms_categories as c')
            ->leftJoin('cms_posts as p', function ($join) {
                $join->on('c.id', '=', 'p.category_id')->whereNull('p.deleted_at');
            })
            ->select('c.id', 'c.name', \Illuminate\Support\Facades\DB::raw('COUNT(p.id) as posts_count'))
            ->whereNull('c.deleted_at')
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('posts_count')
            ->limit(6)
            ->get()
        : collect();

    $maxCatPosts = max(1, (int) $categoryStats->max('posts_count'));

    $recentUsers = \Illuminate\Support\Facades\DB::table('users as u')
        ->leftJoin('groups as g', 'u.groups', '=', 'g.id')
        ->select('u.id', 'u.name', 'u.email', 'u.status', 'u.created_at', 'g.name as group_name')
        ->whereNull('u.deleted_at')
        ->orderByDesc('u.created_at')
        ->limit(5)
        ->get();

    $postToken = base64_encode('extensionsvalley.cms.posts');
    $pageToken = base64_encode('extensionsvalley.cms.pages');
?>

<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Quick Actions <small>LaFlux CMS</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <a href="<?php echo e(route('extensionsvalley.cms.addpost', ['accesstoken' => $postToken])); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> New Post
                </a>
                <a href="<?php echo e(route('extensionsvalley.cms.addpage', ['accesstoken' => $pageToken])); ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-file-o"></i> New Page
                </a>
                <a href="<?php echo e(url('admin/ExtensionsValley/cms/list/cms_categories')); ?>" class="btn btn-info btn-sm">
                    <i class="fa fa-folder-open"></i> Categories
                </a>
                <a href="<?php echo e(url('admin/ExtensionsValley/cms/list/cms_menus')); ?>" class="btn btn-warning btn-sm">
                    <i class="fa fa-bars"></i> Menus
                </a>
                <a href="<?php echo e(route('extensionsvalley.cms.media')); ?>" class="btn btn-dark btn-sm">
                    <i class="fa fa-picture-o"></i> Media Library
                </a>
                <a href="<?php echo e(url('/cms')); ?>" target="_blank" class="btn btn-default btn-sm">
                    <i class="fa fa-external-link"></i> View Live Site
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Recent Posts <small>Latest articles</small></h2>
                <div class="pull-right">
                    <a href="<?php echo e(url('admin/ExtensionsValley/cms/list/cms_posts')); ?>" class="btn btn-xs btn-default">View All</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if($recentPosts->isEmpty()): ?>
                    <p class="text-muted">No posts yet. Create your first post to see it here.</p>
                <?php else: ?>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Publish Date</th>
                            <th width="80">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $recentPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($post->title); ?></strong></td>
                                <td><?php echo e($post->category_name ?: 'Uncategorized'); ?></td>
                                <td>
                                    <?php if((int)$post->status === 1 && $post->published_at && strtotime($post->published_at) > time()): ?>
                                        <span class="label label-info">Scheduled</span>
                                    <?php elseif((int)$post->status === 1): ?>
                                        <span class="label label-success">Published</span>
                                    <?php else: ?>
                                        <span class="label label-default">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($post->published_at ? date('M j, Y', strtotime($post->published_at)) : date('M j, Y', strtotime($post->created_at))); ?></td>
                                <td>
                                    <a href="<?php echo e(route('extensionsvalley.cms.editpost', ['id' => $post->id, 'accesstoken' => $postToken])); ?>" class="btn btn-xs btn-primary">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Posts by Category</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php $__empty_1 = true; $__currentLoopData = $categoryStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $pct = round(($cat->posts_count / $maxCatPosts) * 100); ?>
                    <div style="margin-bottom: 12px;">
                        <div style="display:flex; justify-content:space-between;">
                            <span><?php echo e($cat->name); ?></span>
                            <strong><?php echo e($cat->posts_count); ?></strong>
                        </div>
                        <div class="progress progress_sm" style="margin-top:4px; margin-bottom:0;">
                            <div class="progress-bar bg-green" role="progressbar" style="width: <?php echo e(max($pct, 6)); ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted">No categories created yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Recent Pages</h2>
                <div class="pull-right">
                    <a href="<?php echo e(url('admin/ExtensionsValley/cms/list/cms_pages')); ?>" class="btn btn-xs btn-default">View All</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if($recentPages->isEmpty()): ?>
                    <p class="text-muted">No pages yet.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Page Title</th>
                            <th>Status</th>
                            <th width="80">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $recentPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($page->title); ?></strong><br/>
                                    <small class="text-muted">/page/<?php echo e($page->slug); ?></small>
                                </td>
                                <td>
                                    <?php if((int)$page->status === 1 && $page->published_at && strtotime($page->published_at) > time()): ?>
                                        <span class="label label-info">Scheduled</span>
                                    <?php elseif((int)$page->status === 1): ?>
                                        <span class="label label-success">Published</span>
                                    <?php else: ?>
                                        <span class="label label-default">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('extensionsvalley.cms.editpage', ['id' => $page->id, 'accesstoken' => $pageToken])); ?>" class="btn btn-xs btn-primary">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Recent Users</h2>
                <div class="pull-right">
                    <a href="<?php echo e(url('admin/ExtensionsValley/dashboard/list/users')); ?>" class="btn btn-xs btn-default">Manage Users</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e($usr->name); ?></strong><br/>
                                <small class="text-muted"><?php echo e($usr->email); ?></small>
                            </td>
                            <td><?php echo e($usr->group_name ?: 'User'); ?></td>
                            <td>
                                <?php if((int)$usr->status === 1): ?>
                                    <span class="label label-success">Active</span>
                                <?php else: ?>
                                    <span class="label label-default">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/widgets/activities.blade.php ENDPATH**/ ?>