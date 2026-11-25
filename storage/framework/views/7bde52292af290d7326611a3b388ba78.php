<div class="x_panel">
    <div class="pull-right">
        <?php if(!empty($table->show_toolbar['view'])): ?>
            <button onclick="findRoute('view');" class="btn btn-dark" type="button">
                <?php echo e($table->show_toolbar['view']); ?>

            </button>
        <?php endif; ?>
        <?php if(!empty($table->show_toolbar['add'])): ?>
            <button onclick="findRoute('add');" class="btn btn-success" type="button">
                <?php echo e($table->show_toolbar['add']); ?>

            </button>
        <?php endif; ?>
        <?php if(request()->has('filter_trashed')): ?>
            <?php if(!empty($table->show_toolbar['restore'])): ?>
                <button onclick="findAction('restore');" class="btn btn-warning " type="button">
                    <?php echo e($table->show_toolbar['restore']); ?>

                </button>
            <?php endif; ?>
            <?php if(!empty($table->show_toolbar['forcedelete'])): ?>
                <button onclick="findAction('forcedelete');" class="btn btn-danger" type="button">
                    <?php echo e($table->show_toolbar['forcedelete']); ?>

                </button>
            <?php endif; ?>
        <?php else: ?>
            <?php if(!empty($table->show_toolbar['edit'])): ?>
                <button onclick="findRoute('edit');" class="btn btn-primary" type="button">
                    <?php echo e($table->show_toolbar['edit']); ?>

                </button>
            <?php endif; ?>
            <?php if(!empty($table->show_toolbar['publish'])): ?>
                <button onclick="findAction('enable');" class="btn btn-info" type="button">
                    <?php echo e($table->show_toolbar['publish']); ?>

                </button>
            <?php endif; ?>
            <?php if(!empty($table->show_toolbar['unpublish'])): ?>
                <button onclick="findAction('disable');" class="btn btn-warning " type="button">
                    <?php echo e($table->show_toolbar['unpublish']); ?>

                </button>
            <?php endif; ?>
            <?php if(!empty($table->show_toolbar['trash'])): ?>
                <button onclick="findAction('remove');" class="btn btn-danger" type="button">
                    <?php echo e($table->show_toolbar['trash']); ?>

                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/dashboard/partials/toolbarsection.blade.php ENDPATH**/ ?>