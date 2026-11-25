<?php $__env->startSection('content-header'); ?>
    <?php echo $__env->make('Dashboard::dashboard.partials.headersidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-area'); ?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-picture-o"></i> <?php echo e($title); ?> <small>Upload &amp; manage images and files</small></h2>
                        <div class="clearfix"></div>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div><?php echo e($err); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    
                    <form action="<?php echo e(route('extensionsvalley.cms.media.upload')); ?>" method="POST" enctype="multipart/form-data" id="mediaUploadForm">
                        <?php echo csrf_field(); ?>
                        <div id="mediaDropzone" style="border: 2px dashed #94a3b8; border-radius: 10px; padding: 28px; text-align: center; background: #f8fafc; margin-bottom: 20px; cursor: pointer;">
                            <i class="fa fa-cloud-upload" style="font-size: 36px; color: #2563eb; margin-bottom: 8px;"></i>
                            <h4 style="margin: 4px 0;">Drop files here or click to select</h4>
                            <p class="text-muted" style="margin-bottom: 12px;">Supports JPG, PNG, GIF, WebP, SVG, PDF, Office docs, ZIP, MP4, MP3 (max 10 MB each)</p>
                            <input type="file" name="files[]" id="mediaFilesInput" multiple required style="display:none;">
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('mediaFilesInput').click();">
                                <i class="fa fa-folder-open"></i> Select Files
                            </button>
                            <button type="submit" class="btn btn-success btn-sm" id="uploadSubmitBtn" style="display:none;">
                                <i class="fa fa-upload"></i> Upload Now
                            </button>
                            <div id="selectedFilesHint" class="text-info" style="margin-top: 8px; font-weight: 600;"></div>
                        </div>
                    </form>

                    
                    <form method="GET" action="<?php echo e(route('extensionsvalley.cms.media')); ?>" class="form-inline" style="margin-bottom: 18px;">
                        <div class="form-group">
                            <input type="text" name="q" value="<?php echo e($search); ?>" class="form-control input-sm" placeholder="Search media..." style="width: 240px;">
                        </div>
                        <div class="form-group" style="margin-left: 8px;">
                            <select name="type" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="all" <?php echo e($type === 'all' ? 'selected' : ''); ?>>All Media</option>
                                <option value="images" <?php echo e($type === 'images' ? 'selected' : ''); ?>>Images Only</option>
                                <option value="documents" <?php echo e($type === 'documents' ? 'selected' : ''); ?>>Documents &amp; Other</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm" style="margin-left: 6px;"><i class="fa fa-search"></i> Filter</button>
                        <?php if($search !== '' || $type !== 'all'): ?>
                            <a href="<?php echo e(route('extensionsvalley.cms.media')); ?>" class="btn btn-link btn-sm">Reset</a>
                        <?php endif; ?>
                    </form>

                    
                    <?php if($mediaItems->isEmpty()): ?>
                        <div class="well text-center text-muted" style="padding: 40px;">
                            <i class="fa fa-picture-o" style="font-size: 40px; margin-bottom: 10px;"></i>
                            <p style="margin:0;">No media files found. Upload your first file above.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php $__currentLoopData = $mediaItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 18px;">
                                    <div class="thumbnail" style="margin-bottom:0; cursor:pointer; border-radius:6px; overflow:hidden;"
                                         onclick="openMediaDetail(<?php echo e(json_encode([
                                             'id' => $item->id,
                                             'title' => $item->title,
                                             'alt_text' => $item->alt_text ?? '',
                                             'file_name' => $item->file_name,
                                             'url' => asset($item->file_path),
                                             'mime_type' => $item->mime_type,
                                             'human_size' => $item->human_size,
                                             'is_image' => $item->is_image,
                                             'created_at' => $item->created_at ? $item->created_at->format('M j, Y H:i') : '',
                                             'update_url' => route('extensionsvalley.cms.media.update', ['id' => $item->id]),
                                             'delete_url' => route('extensionsvalley.cms.media.delete', ['id' => $item->id]),
                                         ])); ?>)">
                                        <div style="height: 120px; background: #f1f5f9; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                            <?php if($item->is_image): ?>
                                                <img src="<?php echo e(asset($item->file_path)); ?>" alt="<?php echo e($item->alt_text); ?>" style="max-height:120px; max-width:100%; object-fit:cover;">
                                            <?php else: ?>
                                                <i class="fa fa-file-o" style="font-size: 40px; color: #64748b;"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="caption" style="padding: 8px;">
                                            <div style="font-size:12px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?php echo e($item->title); ?>">
                                                <?php echo e($item->title); ?>

                                            </div>
                                            <small class="text-muted"><?php echo e($item->human_size); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div><?php echo e($mediaItems->links()); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="mediaDetailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Attachment Details</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 text-center" id="mdPreview" style="background:#f8fafc; padding:16px; border-radius:6px; min-height:220px; display:flex; align-items:center; justify-content:center;">
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted" style="font-size:12px;">
                                <strong>File:</strong> <span id="mdFileName"></span><br>
                                <strong>Type:</strong> <span id="mdMime"></span> &middot; <strong>Size:</strong> <span id="mdSize"></span><br>
                                <strong>Uploaded:</strong> <span id="mdDate"></span>
                            </p>
                            <div class="form-group">
                                <label>File URL</label>
                                <div class="input-group">
                                    <input type="text" id="mdUrl" class="form-control input-sm" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-sm" onclick="copyMediaUrl()">Copy URL</button>
                                    </span>
                                </div>
                            </div>
                            <form id="mdUpdateForm" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="mdTitle">Title</label>
                                    <input type="text" name="title" id="mdTitle" class="form-control input-sm" required>
                                </div>
                                <div class="form-group">
                                    <label for="mdAlt">Alt Text</label>
                                    <input type="text" name="alt_text" id="mdAlt" class="form-control input-sm">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Save Details</button>
                            </form>
                            <hr>
                            <form id="mdDeleteForm" method="POST" onsubmit="return confirm('Permanently delete this file?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete Permanently</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        (function () {
            var input = document.getElementById('mediaFilesInput');
            var dropzone = document.getElementById('mediaDropzone');
            var submitBtn = document.getElementById('uploadSubmitBtn');
            var hint = document.getElementById('selectedFilesHint');

            input.addEventListener('change', function () {
                if (this.files.length > 0) {
                    hint.textContent = this.files.length + ' file(s) ready to upload';
                    submitBtn.style.display = 'inline-block';
                }
            });

            dropzone.addEventListener('dragover', function (e) {
                e.preventDefault();
                dropzone.style.borderColor = '#2563eb';
                dropzone.style.background = '#eff6ff';
            });
            dropzone.addEventListener('dragleave', function () {
                dropzone.style.borderColor = '#94a3b8';
                dropzone.style.background = '#f8fafc';
            });
            dropzone.addEventListener('drop', function (e) {
                e.preventDefault();
                dropzone.style.borderColor = '#94a3b8';
                dropzone.style.background = '#f8fafc';
                if (e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    hint.textContent = input.files.length + ' file(s) ready to upload';
                    submitBtn.style.display = 'inline-block';
                }
            });
        })();

        function openMediaDetail(item) {
            var preview = document.getElementById('mdPreview');
            if (item.is_image) {
                preview.innerHTML = '<img src="' + item.url + '" style="max-width:100%; max-height:280px; border-radius:4px;">';
            } else {
                preview.innerHTML = '<i class="fa fa-file-o" style="font-size:64px; color:#64748b;"></i>';
            }
            document.getElementById('mdFileName').textContent = item.file_name;
            document.getElementById('mdMime').textContent = item.mime_type || 'unknown';
            document.getElementById('mdSize').textContent = item.human_size;
            document.getElementById('mdDate').textContent = item.created_at;
            document.getElementById('mdUrl').value = item.url;
            document.getElementById('mdTitle').value = item.title;
            document.getElementById('mdAlt').value = item.alt_text;
            document.getElementById('mdUpdateForm').action = item.update_url;
            document.getElementById('mdDeleteForm').action = item.delete_url;
            jQuery('#mediaDetailModal').modal('show');
        }

        function copyMediaUrl() {
            var el = document.getElementById('mdUrl');
            el.select();
            document.execCommand('copy');
            alert('URL copied to clipboard!');
        }
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Dashboard::dashboard.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/admin/media/index.blade.php ENDPATH**/ ?>