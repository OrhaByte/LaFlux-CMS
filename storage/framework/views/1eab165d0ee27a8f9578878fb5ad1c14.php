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

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Installed Packages</h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li class="pull-right">
                            </li>
                        </ul>

                           <!--  <a href="<?php echo e(route('extensionsvalley.admin.addnewpackage')); ?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Install or Update Package</a> -->
                            <a class="btn btn-round btn-dark pull-right"
                            href="<?php echo e(route('extensionsvalley.admin.manageextension',['status' => 1])); ?>" >Active  : <?php echo e($full_extensions->where('status',1)->count()); ?></a>
                            <a class="btn btn-round btn-dark pull-right"
                            href="<?php echo e(route('extensionsvalley.admin.manageextension',['status' => 0])); ?>" >Disabled :  <?php echo e($full_extensions->where('status',0)->count()); ?></a>
                            <a class="btn btn-round btn-dark pull-right"
                            href="<?php echo e(route('extensionsvalley.admin.manageextension')); ?>" >Total :
                            <?php echo e($full_extensions->count()); ?></a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                    <?php $count = 1;?>
                    <div class="row">
                    <?php $__currentLoopData = $extensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if($count % 2 != 0): ?>
                        <div class="row">
                      <?php endif; ?>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12 profile_details">
                        <div class="well profile_view <?php if($items->status == 1): ?> active_bg <?php else: ?> disable_bg <?php endif; ?>">
                          <div class="col-sm-12">
                            <h4 class="brief"><i><?php echo e(ucfirst($items->vendor)); ?></i></h4>
                            <div class="left col-xs-8">
                              <h2><?php echo e(ucfirst($items->name)); ?></h2>
                              <p><strong>Version: </strong> <?php echo e($items->version); ?>. </p>
                              <ul class="list-unstyled">
                                <li>Author: <?php echo e(ucfirst($items->author)); ?></li>
                                <li>Type: <?php echo e($items->package_type); ?> </li>
                                <li>Installed: <?php echo e(date('Y-m-d',strtotime($items->created_at))); ?>

                                <?php if(!empty($items->updated_at)): ?>
                                  / <?php echo e(date('Y-m-d',strtotime($items->updated_at))); ?></li>
                                <?php endif; ?>
                              </ul>
                            </div>

                            <div class="right col-xs-4 text-center">
                              <?php if(file_exists(base_path()."/public/".$items->icon)): ?>
                                <img src="<?php echo e(URL::to('/')); ?>/<?php echo e($items->icon); ?>" alt="" class="img-circle img-responsive">
                              <?php else: ?>
                                <img src="<?php echo e(URL::to('/')); ?>/packages/extensionsvalley/dashboard/images/laflux-logo.png" alt="" class="img-circle img-responsive">
                              <?php endif; ?>
                            </div>
                          </div>
                          <div class="col-xs-12 bottom">
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php if(!empty($items->website)): ?>
                              <a href="<?php echo e($items->website); ?>" target="_blank" class="btn btn-dark btn-xs">
                                 Repository
                              </a>
                            <?php endif; ?>
                            <?php if($items->is_paid == 0): ?>
                              <a class="btn btn-warning btn-xs">Free</a>
                            <?php else: ?>
                              <a class="btn btn-info btn-xs">Paid</a>
                            <?php endif; ?>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                 <?php if($items->vendor == "ExtensionsValley"
                                 && ($items->name == "Dashboard")): ?>
                                    <a href="<?php echo e($items->website); ?>" target="_blank" class="btn btn-dark btn-xs pull-right">Website</a>
                                 <?php else: ?>

                                   <?php if($items->status == 0 && $items->name != "Basetheme" ): ?>
                                      <?php
                                      $url = ('uninstallpackage/'.$items->id);
                                      ?>
                                    <a class="btn btn-danger btn-xs pull-right"
                                    onclick="uninstallPackage('<?php echo e($url); ?>')">Uninstall</a>
                                   <?php endif; ?>
                                   <?php if($items->status == 1 && $items->name != "Basetheme" ): ?>
                                    <!-- <a class="btn btn-success btn-xs pull-right <?php if($items->package_type == 'Core Theme'): ?> hide <?php endif; ?>"
                                    href="<?php echo e(route('extensionsvalley.admin.disablepackage',['id'=> $items->id])); ?>">Deactivate</a> -->
                                   <?php elseif($items->status == 0): ?>
                                    <a class="btn btn-primary btn-xs pull-right"
                                    href="<?php echo e(route('extensionsvalley.admin.activatepackage',['id'=> $items->id])); ?>">Activate</a>
                                   <?php else: ?>
                                      <a href="<?php echo e($items->website); ?>" target="_blank" class="btn btn-dark btn-xs pull-right">Website</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                       <?php if($count % 2 == 0): ?>
                        </div>
                      <?php endif; ?>
                      <?php $count++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                     </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/extension/default.blade.php ENDPATH**/ ?>