<?php
    $totalUsers = \Illuminate\Support\Facades\DB::table('users')->whereNull('deleted_at')->count();
    $activeUsers = \Illuminate\Support\Facades\DB::table('users')->whereNull('deleted_at')->where('status', 1)->count();

    $hasPosts = \Illuminate\Support\Facades\Schema::hasTable('cms_posts');
    $hasPages = \Illuminate\Support\Facades\Schema::hasTable('cms_pages');
    $hasCategories = \Illuminate\Support\Facades\Schema::hasTable('cms_categories');
    $hasTags = \Illuminate\Support\Facades\Schema::hasTable('cms_tags');
    $hasMenus = \Illuminate\Support\Facades\Schema::hasTable('cms_menus');

    $totalPosts = $hasPosts ? \Illuminate\Support\Facades\DB::table('cms_posts')->whereNull('deleted_at')->count() : 0;
    $publishedPosts = $hasPosts ? \Illuminate\Support\Facades\DB::table('cms_posts')->whereNull('deleted_at')->where('status', 1)->count() : 0;

    $totalPages = $hasPages ? \Illuminate\Support\Facades\DB::table('cms_pages')->whereNull('deleted_at')->count() : 0;
    $publishedPages = $hasPages ? \Illuminate\Support\Facades\DB::table('cms_pages')->whereNull('deleted_at')->where('status', 1)->count() : 0;

    $totalCategories = $hasCategories ? \Illuminate\Support\Facades\DB::table('cms_categories')->whereNull('deleted_at')->count() : 0;
    $activeCategories = $hasCategories ? \Illuminate\Support\Facades\DB::table('cms_categories')->whereNull('deleted_at')->where('status', 1)->count() : 0;

    $totalTags = $hasTags ? \Illuminate\Support\Facades\DB::table('cms_tags')->whereNull('deleted_at')->count() : 0;
    $totalMenus = $hasMenus ? \Illuminate\Support\Facades\DB::table('cms_menus')->whereNull('deleted_at')->count() : 0;
    $totalMenuItems = \Illuminate\Support\Facades\Schema::hasTable('cms_menu_items')
        ? \Illuminate\Support\Facades\DB::table('cms_menu_items')->count()
        : 0;
?>

<!-- top tiles -->
<div class="tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-users"></i> Total Users</span>
        <div class="count"><?php echo e(number_format($totalUsers)); ?></div>
        <span class="count_bottom"><i class="green"><?php echo e(number_format($activeUsers)); ?></i> Active</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-file-text-o"></i> Total Posts</span>
        <div class="count green"><?php echo e(number_format($totalPosts)); ?></div>
        <span class="count_bottom"><i class="green"><?php echo e(number_format($publishedPosts)); ?></i> Published</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-files-o"></i> Total Pages</span>
        <div class="count"><?php echo e(number_format($totalPages)); ?></div>
        <span class="count_bottom"><i class="green"><?php echo e(number_format($publishedPages)); ?></i> Published</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-folder-open-o"></i> Categories</span>
        <div class="count"><?php echo e(number_format($totalCategories)); ?></div>
        <span class="count_bottom"><i class="green"><?php echo e(number_format($activeCategories)); ?></i> Active</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-tags"></i> Tags</span>
        <div class="count"><?php echo e(number_format($totalTags)); ?></div>
        <span class="count_bottom">Post Taxonomy</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-bars"></i> Menus</span>
        <div class="count"><?php echo e(number_format($totalMenus)); ?></div>
        <span class="count_bottom"><i class="green"><?php echo e(number_format($totalMenuItems)); ?></i> Links</span>
    </div>
</div>
<!-- /top tiles -->
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/widgets/tiles.blade.php ENDPATH**/ ?>