<?php $__env->startSection('content-header'); ?>
    <?php echo $__env->make('Dashboard::dashboard.partials.headersidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
            $action = isset($page) ? 'extensionsvalley.cms.updatepage' : 'extensionsvalley.cms.savepage';
            $readonly = isset($viewmode) ? 'readonly' : '';
            $disabled = isset($viewmode) ? 'disabled' : '';
            $publishedAtVal = old('published_at', isset($page->published_at) ? $page->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'));
        ?>
        <div class="x_panel">
            <form action="<?php echo e(route($action)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group <?php echo e($errors->has('title') ? 'has-error' : ''); ?>">
                            <label for="title">Page Title <span class="error_red">*</span></label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="<?php echo e(old('title', $page->title ?? '')); ?>" placeholder="Page Title" required <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('title')); ?></span>
                        </div>

                        <div class="form-group <?php echo e($errors->has('slug') ? 'has-error' : ''); ?>">
                            <label for="slug">Slug <small class="text-muted">(leave blank to auto-generate)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="<?php echo e(old('slug', $page->slug ?? '')); ?>" placeholder="page-slug" <?php echo e($readonly); ?>>
                            <span class="error_red"><?php echo e($errors->first('slug')); ?></span>
                        </div>

                        <div class="form-group">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                                <label for="content" style="margin:0;">Content</label>
                                <?php if(!isset($viewmode)): ?>
                                    <button type="button" class="btn btn-default btn-xs" onclick="openMediaPicker('editor')">
                                        <i class="fa fa-picture-o"></i> Add Media to Content
                                    </button>
                                <?php endif; ?>
                            </div>
                            <textarea name="content" id="content" class="form-control cms-editor" rows="12" <?php echo e($readonly); ?>><?php echo e(old('content', $page->content ?? '')); ?></textarea>
                        </div>

                        <hr/>
                        <h4>SEO Meta</h4>
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                   value="<?php echo e(old('meta_title', $page->meta_title ?? '')); ?>" <?php echo e($readonly); ?>>
                        </div>
                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords <small class="text-muted">(comma separated)</small></label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                   value="<?php echo e(old('meta_keywords', $page->meta_keywords ?? '')); ?>" placeholder="keyword1, keyword2, keyword3" <?php echo e($readonly); ?>>
                        </div>
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" class="form-control" <?php echo e($readonly); ?>><?php echo e(old('meta_description', $page->meta_description ?? '')); ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Publishing</strong></div>
                            <div class="panel-body">
                                <?php echo $__env->make('Cms::admin.partials.language_select', ['currentLanguageCode' => $page->language_code ?? null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <div class="form-group">
                                    <label for="status">Status <span class="error_red">*</span></label>
                                    <select name="status" id="status" class="form-control" required <?php echo e($disabled); ?>>
                                        <option value="1" <?php echo e(old('status', $page->status ?? 1) == 1 ? 'selected' : ''); ?>>Publish</option>
                                        <option value="0" <?php echo e(old('status', $page->status ?? 1) == 0 ? 'selected' : ''); ?>>Draft / Unpublish</option>
                                    </select>
                                </div>
                                <div class="form-group <?php echo e($errors->has('published_at') ? 'has-error' : ''); ?>">
                                    <label for="published_at">Publish Date &amp; Time <small class="text-muted">(future date = scheduled)</small></label>
                                    <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                                           value="<?php echo e($publishedAtVal); ?>" <?php echo e($readonly); ?>>
                                    <span class="error_red"><?php echo e($errors->first('published_at')); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="ordering">Page Order</label>
                                    <input type="number" name="ordering" id="ordering" class="form-control"
                                           value="<?php echo e(old('ordering', $page->ordering ?? 0)); ?>" <?php echo e($readonly); ?>>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Featured Image</strong></div>
                            <div class="panel-body">
                                <div id="featuredImagePreviewBox" style="margin-bottom:10px;">
                                    <?php if(!empty($page->featured_image)): ?>
                                        <img src="<?php echo e(asset($page->featured_image)); ?>" alt="Featured Image" class="img-responsive img-thumbnail" style="max-height:180px;">
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($page->featured_image) && !isset($viewmode)): ?>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="remove_featured_image" value="1"> Remove current image</label>
                                    </div>
                                <?php endif; ?>
                                <?php if(!isset($viewmode)): ?>
                                    <input type="hidden" name="selected_media_path" id="selected_media_path" value="">
                                    <button type="button" class="btn btn-default btn-sm btn-block" style="margin-bottom:8px;" onclick="openMediaPicker()">
                                        <i class="fa fa-picture-o"></i> Choose from Media Library
                                    </button>
                                    <input type="file" name="featured_image" id="featured_image" class="form-control" accept="image/*">
                                    <span class="error_red"><?php echo e($errors->first('featured_image')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(isset($page->id)): ?>
                    <input type="hidden" name="page_id" value="<?php echo e($page->id); ?>"/>
                <?php endif; ?>
                <input type="hidden" name="accesstoken" value="<?php echo e(request()->get('accesstoken', '')); ?>"/>

                <div class="row">
                    <div class="col-md-12">
                        <a href="<?php echo e(url('admin/ExtensionsValley/cms/list/cms_pages')); ?>" class="btn btn-default">Cancel</a>
                        <?php if(!isset($viewmode)): ?>
                            <button type="submit" class="btn btn-primary">Save Page</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php echo $__env->make('Cms::admin.partials.tinymce', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('Cms::admin.partials.mediapicker', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/admin/page/form.blade.php ENDPATH**/ ?>