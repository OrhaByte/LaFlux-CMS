<!DOCTYPE html>
<html lang="<?php echo e($currentLang->code ?? 'en'); ?>" dir="<?php echo e($currentLang->direction ?? 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($metaTitle ?? 'CMS'); ?> | <?php echo e(\WebConf::get('site_name') ?: config('app.name', 'Laravel CMS')); ?></title>
    <?php if(!empty($metaKeywords)): ?>
        <meta name="keywords" content="<?php echo e($metaKeywords); ?>">
    <?php endif; ?>
    <?php if(!empty($metaDescription)): ?>
        <meta name="description" content="<?php echo e($metaDescription); ?>">
    <?php endif; ?>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; color: #222; background: #f8f9fa; line-height: 1.6; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        header { background: #1e293b; color: #fff; padding: 0 20px; }
        .nav-wrap { max-width: 1100px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; min-height: 60px; flex-wrap: wrap; }
        .brand { font-size: 1.25rem; font-weight: 700; color: #fff; }
        .nav-menu { list-style: none; display: flex; gap: 20px; margin: 0; padding: 0; }
        .nav-menu li { position: relative; }
        .nav-menu > li > a { color: #e2e8f0; font-weight: 500; padding: 8px 4px; display: inline-block; }
        .nav-menu > li > a:hover { color: #fff; text-decoration: none; }
        .nav-menu ul { display: none; position: absolute; top: 100%; left: 0; background: #1e293b; list-style: none; padding: 6px 0; margin: 0; min-width: 180px; border-radius: 4px; box-shadow: 0 6px 16px rgba(0,0,0,.2); z-index: 20; }
        .nav-menu li:hover > ul { display: block; }
        .nav-menu ul li a { display: block; padding: 6px 14px; color: #e2e8f0; }
        .nav-menu ul li a:hover { background: #334155; text-decoration: none; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 300px; gap: 30px; }
        @media (max-width: 820px) { .container { grid-template-columns: 1fr; } }
        .card { background: #fff; border-radius: 8px; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
        .card img.featured { width: 100%; max-height: 360px; object-fit: cover; border-radius: 6px; margin-bottom: 16px; }
        .meta { font-size: 0.875rem; color: #64748b; margin-bottom: 12px; }
        .badge { display: inline-block; background: #e2e8f0; color: #334155; padding: 2px 10px; border-radius: 999px; font-size: 0.8rem; margin-right: 6px; margin-bottom: 6px; }
        .sidebar h3 { margin-top: 0; font-size: 1rem; text-transform: uppercase; letter-spacing: .04em; color: #475569; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li { padding: 6px 0; border-bottom: 1px solid #f1f5f9; }
        footer { background: #0f172a; color: #94a3b8; padding: 24px 20px; margin-top: 40px; }
        .footer-inner { max-width: 1100px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .footer-menu { list-style: none; display: flex; gap: 16px; margin: 0; padding: 0; }
        .footer-menu a { color: #cbd5e1; }
    </style>
</head>
<body>
<header>
    <div class="nav-wrap">
        <a class="brand" href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('cms')); ?>">
            <?php echo e(\WebConf::get('site_name') ?: config('app.name', 'Laravel CMS')); ?>

        </a>
        <nav>
            <ul class="nav-menu">
                <?php if(!empty($headerMenu) && $headerMenu->rootItems->isNotEmpty()): ?>
                    <?php $__currentLoopData = $headerMenu->rootItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e($item->resolved_url); ?>" target="<?php echo e($item->target); ?>"><?php echo e($item->title); ?></a>
                            <?php if($item->children->isNotEmpty()): ?>
                                <ul>
                                    <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="<?php echo e($child->resolved_url); ?>" target="<?php echo e($child->target); ?>"><?php echo e($child->title); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <li><a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('cms')); ?>">Home</a></li>
                <?php endif; ?>
                <?php if(!empty($isMultilingual) && !empty($activeLanguages)): ?>
                    <li>
                        <a href="javascript:;"><?php echo e(strtoupper($currentLang->code ?? 'en')); ?> &#9662;</a>
                        <ul>
                            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $langOpt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('cms', $langOpt->code)); ?>">
                                        <?php echo e($langOpt->name); ?> (<?php echo e(strtoupper($langOpt->code)); ?>)
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <li><a href="<?php echo e(url('/admin')); ?>">Admin</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    <section>
        <?php echo $__env->yieldContent('content'); ?>
    </section>

    <aside class="sidebar">
        <div class="card">
            <h3>Categories</h3>
            <?php if(!empty($sidebarCategories) && $sidebarCategories->isNotEmpty()): ?>
                <ul>
                    <?php $__currentLoopData = $sidebarCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $cat->slug)); ?>"><?php echo e($cat->name); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p class="meta">No categories yet.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>Tags</h3>
            <?php if(!empty($sidebarTags) && $sidebarTags->isNotEmpty()): ?>
                <div>
                    <?php $__currentLoopData = $sidebarTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a class="badge" href="<?php echo e(\ExtensionsValley\Cms\Helpers\CmsHelper::url('tag/' . $t->slug)); ?>">#<?php echo e($t->name); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="meta">No tags yet.</p>
            <?php endif; ?>
        </div>
    </aside>
</main>

<footer>
    <div class="footer-inner">
        <div>&copy; <?php echo e(date('Y')); ?> <?php echo e(\WebConf::get('site_name') ?: config('app.name', 'Laravel CMS')); ?></div>
        <?php if(!empty($footerMenu) && $footerMenu->rootItems->isNotEmpty()): ?>
            <ul class="footer-menu">
                <?php $__currentLoopData = $footerMenu->rootItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e($item->resolved_url); ?>" target="<?php echo e($item->target); ?>"><?php echo e($item->title); ?></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>
</footer>
</body>
</html>

<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/frontend/layout.blade.php ENDPATH**/ ?>