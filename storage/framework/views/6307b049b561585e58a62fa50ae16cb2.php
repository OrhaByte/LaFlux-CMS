<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

    <div class="menu_section">

        <?php if(!\Schema::hasTable('acl_permission')): ?>
            <?php
            $menugroups = new Illuminate\Support\Collection;
            \Event::dispatch('admin.menu.groups', [$menugroups]);

            ?>
            <ul class="nav side-menu">
                <?php $__currentLoopData = $menugroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a><?php echo $key['menu_icon']; ?> <?php echo e($key['menu_text']); ?> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php $__currentLoopData = $key['sub_menu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subkey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e($subkey['link']); ?>"><?php echo e($subkey['menu_text']); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php else: ?>
            <?php
            $main_menu = \DB::table('acl_permission')
                    ->Where('group_id', \Auth::guard('admin')->user()->groups)
                    ->Where('view',1)
                    ->orderBy('ordering', 'ASC')
                    ->get();


            ?>
            <ul class="nav side-menu">
                <?php $__currentLoopData = $main_menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($key->parent_menu == 0): ?>
                        <li>
                            <a><?php echo $key->icon; ?> <?php echo e($key->menu_text); ?><span class="fa fa-chevron-down"></span></a>
                            <?php endif; ?>
                            <?php
                            $sub_menu = $main_menu->Where('parent_menu', $key->id)->all();
                            ?>
                            <ul class="nav child_menu">
                                <?php $__currentLoopData = $sub_menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subkey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><a href="<?php echo e($subkey->link); ?>"><?php echo e($subkey->menu_text); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>

</div>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/partials/sidemenu.blade.php ENDPATH**/ ?>