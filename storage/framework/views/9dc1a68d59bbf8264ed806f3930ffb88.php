<?php $__env->startSection('content-header'); ?>

    <!-- Navigation Starts-->
    <?php echo $__env->make('Dashboard::dashboard.partials.headersidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Navigation Ends-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-area'); ?>
    <div class="right_col" role="main">

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2><?php echo e($title); ?></h2>
                </div>
            </div>
        </div>
        <?php
        $action = 'extensionsvalley.admin.setpermission';
        ?>
        <div class="x_panel">
            <form action="<?php echo e(route($action)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                    <div class="form-group <?php echo e($errors->has('group') ? 'has-error' : ''); ?> control-required">
                        <label for="groups_id">User Group</label>
                        <select name="groups_id" id="groups_id" class="form-control js-permission-role select2" required>
                            <option value="0">Choose Group</option>
                            <option value="1" <?php echo e((request()->get('id') == '1') ? 'selected' : ''); ?>>Super Admin</option>
                            <?php $__currentLoopData = ExtensionsValley\Dashboard\Models\Group::getGroups()->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e((request()->get('id') == $key) ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group control-required" style="margin-top: 25px;">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group control-required">
                        <?php

                        $menugroups = new Illuminate\Support\Collection;
                        \Event::dispatch('admin.menu.groups', [$menugroups]);

                        ?>
                        <table class="table table-responsive">
                            <tr>

                                <th>Menus</th>
                                <th>Order</th>
                                <th>
                                    <input type="checkbox" id="view_all_permissions" class="flat ch_box">
                                    View
                                </th>
                                <th>
                                    <input type="checkbox" id="add_all_permissions" class="flat ch_box">
                                    Add
                                </th>
                                <th>
                                    <input type="checkbox" id="edit_all_permissions" class="flat ch_box">
                                    Edit
                                </th>
                                <th>
                                    <input type="checkbox" id="delete_all_permissions" class="flat ch_box">
                                    Delete
                                </th>
                                <th>
                                    <input type="checkbox" id="check_all_permissions" class="flat ch_box">
                                    All
                                </th>

                            </tr>
                            <?php if(sizeof($menugroups->all())): ?>
                                <?php $count = 1;
                                    $maincounter = 0;
                                    $subcounter = 0;
                                ?>
                                <?php $__currentLoopData = $menugroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php
                                    if (sizeof($result)) {
                                        $main_menu_data = $result->Where('acl_key', $key['acl_key'])
                                                ->Where('group_id', request()->get('id'))
                                                ->first();
                                    } else {
                                        $main_menu_data = [];
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $key['menu_icon']; ?> <?php echo e($key['menu_text']); ?>

                                            <input type="hidden" name="main_menu_text[]" value="<?php echo e($key['menu_text']); ?>"/>
                                            <input type="hidden" name="main_menu_icon[]" value="<?php echo e($key['menu_icon']); ?>"/>
                                            <input type="hidden" name="main_menu_acl_key[]"
                                                   value="<?php echo e($key['acl_key']); ?>"/>
                                        </td>
                                        <td><input type="number" name="main_menu_order[]"
                                                   <?php if(!empty($main_menu_data->ordering)): ?>
                                                   value="<?php echo e($main_menu_data->ordering); ?>"
                                                    <?php endif; ?>
                                            /></td>
                                        <td>
                                            <input type="checkbox"
                                                   <?php if(!empty($main_menu_data->view)): ?> checked="checked"
                                                   <?php endif; ?>
                                                   name="main_menu_view_<?php echo e($maincounter); ?>"
                                                   class="view_chk flat ch_box"/>

                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php if(!empty($key['sub_menu'])): ?>
                                        <?php $__currentLoopData = $key['sub_menu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subkey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <?php
                                            if (sizeof($result)) {
                                                $sub_menu_data = $result->Where('acl_key', $subkey['acl_key'])
                                                        ->Where('group_id', request()->get('id'))
                                                        ->first();
                                            } else {
                                                $sub_menu_data = [];
                                            }
                                            ?>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php echo e($subkey['menu_text']); ?>

                                                    <input type="hidden" name="sub_menu_link[]"
                                                           value="<?php echo e($subkey['link']); ?>"/>
                                                    <input type="hidden" name="sub_menu_text[]"
                                                           value="<?php echo e($subkey['menu_text']); ?>"/>
                                                    <input type="hidden" name="sub_menu_acl_key[]"
                                                           value="<?php echo e($subkey['acl_key']); ?>"/>
                                                    <input type="hidden" name="sub_menu_parent_acl_key[]"
                                                           value="<?php echo e($key['acl_key']); ?>"/>
                                                </td>
                                                <td>
                                                    <input type="number" name="sub_menu_order[]"
                                                           <?php if(!empty($sub_menu_data->ordering)): ?>
                                                           value="<?php echo e($sub_menu_data->ordering); ?>"
                                                            <?php endif; ?>
                                                    />
                                                </td>
                                                <td>
                                                    <?php $subcount = $count - 1;?>
                                                    <input type="checkbox" name="sub_menu_view_<?php echo e($subcounter); ?>"
                                                           class="view_chk flat ch_box view_<?php echo e($count); ?>"
                                                           <?php if(!empty($sub_menu_data->view)): ?> checked="checked" <?php endif; ?> />
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="sub_menu_add_<?php echo e($subcounter); ?>"
                                                           class="add_chk flat ch_box add_<?php echo e($count); ?>"
                                                           <?php if(!empty($sub_menu_data->adding)): ?> checked="checked" <?php endif; ?> />
                                                </td>
                                                <td>
                                                    <input type="checkbox"
                                                    name="sub_menu_edit_<?php echo e($subcounter); ?>"
                                                           class="edit_chk flat ch_box edit_<?php echo e($count); ?>"
                                                           <?php if(!empty($sub_menu_data->edit)): ?> checked="checked" <?php endif; ?> />
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="sub_menu_delete_<?php echo e($subcounter); ?>"
                                                           class="del_chk flat ch_box del_<?php echo e($count); ?>"
                                                           <?php if(!empty($sub_menu_data->trash)): ?> checked="checked" <?php endif; ?> />
                                                </td>
                                                <td><input type="checkbox" id="check_current_row_<?php echo e($count); ?>"
                                                           class="chk_current_row flat ch_box"/></td>
                                            </tr>
                                            <?php   $count++;
                                                    $subcounter++;
                                            ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                        <?php $maincounter++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/user/aclform.blade.php ENDPATH**/ ?>