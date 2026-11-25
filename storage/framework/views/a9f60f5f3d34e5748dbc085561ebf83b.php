<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        <?php
                        $profile_image = 'packages/extensionsvalley/dashboard/images/profile/user.png';
                        $icon = \DB::table('user_profile')->Where('user_id', \Auth::guard('admin')->user()->id)->value('media');
                        if (!empty($icon)) {
                            $profile_image = $icon;
                        }
                        ?>
                        <img src="<?php echo e(URL::to('/')); ?>/<?php echo e($profile_image); ?>" alt="">
                        <?php echo e(ucfirst(\Auth::guard('admin')->user()->name)); ?>

                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="<?php echo e(route('extensionsvalley.admin.editusersprofile')); ?>"> Profile</a></li>
                        <?php if(\Auth::guard('admin')->user()->groups == 1): ?>
                            <li>
                                <a href="<?php echo e(route('extensionsvalley.admin.gensettings')); ?>">
                                    <span>General Settings</span>
                                </a>
                            </li>
                            <li><a href="<?php echo e(route('extensionsvalley.admin.permission')); ?>">Access Permission</a></li>
                             <li><a href="<?php echo e(route('extensionsvalley.admin.manageextension')); ?>">Extension Manager</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo e(route('extensionsvalley.admin.logout')); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
                <?php echo $__env->make('Dashboard::dashboard.partials.topnavbuilder', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                 <li><a href="<?php echo e(url('/')); ?>" target="_blank"><b>Visit Front Page</b></a>
                 </li>
            </ul>
        </nav>
    </div>
</div>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/partials/topnavigation.blade.php ENDPATH**/ ?>