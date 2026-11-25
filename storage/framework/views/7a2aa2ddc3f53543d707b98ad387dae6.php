<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Advanced Search Filter</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?php echo e(Request::url()); ?>" method="GET">

            <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                <label for="filter_group">User Groups</label>
                <select name="filter_group" id="filter_group" class="form-control select2">
                    <option value="0">--Select--</option>
                    <?php $__currentLoopData = ExtensionsValley\Dashboard\Models\Group::getGroups()->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e((request()->get('filter_group') == $key) ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                <label for="filter_status">User Status</label>
                <select name="filter_status" id="filter_status" class="form-control js-example-responsive filter_status select2">
                    <option value="-1">--Select--</option>
                    <option value="0" <?php echo e((request()->get('filter_status') == '0') ? 'selected' : ''); ?>>Unpublished</option>
                    <option value="1" <?php echo e((request()->get('filter_status') == '1') ? 'selected' : ''); ?>>Published</option>
                </select>
            </div>
          
                <div class="col-md-4 col-sm-6 col-xs-12 form-group"><br/>
                    <a href="<?php echo e(Request::url()); ?>" class="btn btn-primary">Clear</a>
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
           

            </form>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/user/advancedfilters/usersfilter.blade.php ENDPATH**/ ?>