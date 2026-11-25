<?php $__env->startSection('content-header'); ?>

    <!-- Navigation Starts-->
    <?php echo $__env->make('Dashboard::dashboard.partials.headersidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Navigation Ends-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-area'); ?>
    <div class="right_col" role="main">

        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="x_panel">
                    <h2><?php echo e($title); ?></h2>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <?php if(!empty($table->show_toolbar)): ?>
                    <?php echo $__env->make('Dashboard::dashboard.partials.toolbarsection', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <?php if(!empty($table->advanced_filter)): ?>
                <?php if ($__env->exists($table->advanced_filter['layout'])) echo $__env->make($table->advanced_filter['layout'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>


        <form action="<?php echo e(route('extensionsvalley.admin.actions')); ?>" method="POST" class="admin_form" id="admin_listing" name="admin_listing">
        <?php echo csrf_field(); ?>
        <div class="x_panel">
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered bulk_action" id="datatable-buttons">
                    <thead>
                    <tr>
                        <th width="7%"><input class="flat" type="checkbox" id="checkall" name="checkall"/></th>
                        <?php $__currentLoopData = $table->listable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th><?php echo e($values); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <?php if(sizeof($table->routes)): ?>
            <?php if(isset($table->routes['add_route'])): ?>
                <input type="hidden" name="add_route" id="add_route" value="<?php echo e($table->routes['add_route']); ?>/"/>
            <?php endif; ?>
            <?php if(isset($table->routes['edit_route'])): ?>
                <input type="hidden" name="edit_route" id="edit_route" value="<?php echo e($table->routes['edit_route']); ?>/"/>
            <?php endif; ?>
            <?php if(isset($table->routes['view_route'])): ?>
                <input type="hidden" name="view_route" id="view_route" value="<?php echo e($table->routes['view_route']); ?>/"/>
            <?php endif; ?>
        <?php endif; ?>
        <input type="hidden" name="table_name" id="table_name" value="<?php echo e($table->table_name); ?>"/>
        <input type="hidden" name="acl_key" id="acl_key" value="<?php echo e(base64_encode($table->acl_key)); ?>"/>
        <input type="hidden" name="return_url" id="return_url" value="<?php echo e(base64_encode(Request::url())); ?>"/>
        <input type="hidden" name="model_name" id="model_name" value="<?php echo e(base64_encode($table->model_name)); ?>"/>
        <input type="hidden" name="action_type" id="action_type" value=""/>
        <input type="hidden" name="others" id="others" value="<?php if(request()->has('others')): ?> 1 <?php else: ?> 0 <?php endif; ?>"/>

        </form>

        <form name="addeditview" id="addeditview"  action=""  method="get">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="accesstoken" id="accesstoken" value="<?php echo e(base64_encode($table->acl_key)); ?>"/>
        <input type="hidden" name="domain_url" id="domain_url" value="<?php echo e(url('/')); ?>"/>
        <input type="hidden" id="filter_trashed" value="<?php echo e(request()->has('filter_trashed') ? 1 : 0); ?>">
        </form>


    </div>
    <script>
        jQuery(document).ready(function () {
            var _token = jQuery('name[_token]').val();
            var handleDataTableButtons = function () {
                jQuery('#datatable-buttons').DataTable({
                    dom: "Bfrtip",
                    buttons: ['pageLength',
                        {
                            extend: "copy",
                            className: "btn-md"
                        },
                        {
                            extend: "csv",
                            className: "btn-md"
                        },
                        /* {
                         extend: "excel",
                         className: "btn-md"
                         },*/
                        {
                            extend: "pdfHtml5",
                            className: "btn-md"
                        },
                        {
                            extend: "print",
                            className: "btn-md"
                        },
                        <?php if(request()->has('filter_trashed')): ?>
                        {
                            text: ' Clear Filter',
                            action: function ( e, dt, node, config ) {
                                var link = document.location.href.replace(document.location.search,"");
                                document.location.href = link;
                            }
                        }
                        <?php else: ?>
                        {   text: '<i class="fa fa-trash"></i> Trashed',
                             action: function ( e, dt, node, config ) {
                                document.location.href +="?filter_trashed=1";
                            }
                        }
                        <?php endif; ?>
                    ],
                    "responsive": true,
                    "columnDefs": [{"targets": 0, "orderable": false}],
                    "aaSorting": [[1, 'desc']],
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        url: "<?php echo e(route('extensionsvalley.admin.ajax.list',['namespace' => $table->namespace,'tables' => $table->table_name ])); ?>",
                        data: function (d) {
                            d.customsearch = jQuery("input[type=search]").val(),
                                    <?php if(!empty($table->advanced_filter['filters'])): ?>
                                            <?php $__currentLoopData = $table->advanced_filter['filters']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filterkey=>$filterval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            d.<?php echo e($filterkey); ?> = jQuery.trim(jQuery("#<?php echo e($filterkey); ?>").val()),
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            d.others = jQuery.trim(jQuery("#others").val());
                        }
                    },

                    "columns": [
                        {data: 'sl', name: 'id'},
                            <?php $__currentLoopData = $table->listable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            data: '<?php echo e($key); ?>', name: '<?php echo e($key); ?>'
                        },
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    ]


                });
            };
            TableManageButtons = function () {
                "use strict";
                return {
                    init: function () {
                        handleDataTableButtons();
                    }
                };
            }();
            TableManageButtons.init();

            jQuery('#datatable-buttons').on('draw.dt', function () {
                jQuery('input').iCheck({
                    checkboxClass: 'icheckbox_flat-green'
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/partials/table.blade.php ENDPATH**/ ?>