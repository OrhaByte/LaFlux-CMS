<div class="modal fade" id="cmsMediaPickerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-picture-o"></i> <span id="mediaPickerHeading">Choose from Media Library</span></h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom:12px;">
                    <div class="col-md-6">
                        <input type="text" id="mediaPickerSearch" class="form-control input-sm" placeholder="Search images..." oninput="loadMediaPicker()">
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="<?php echo e(route('extensionsvalley.cms.media')); ?>" target="_blank" class="btn btn-default btn-sm">
                            <i class="fa fa-upload"></i> Open Media Library
                        </a>
                    </div>
                </div>
                <div id="mediaPickerGrid" class="row" style="max-height: 420px; overflow-y: auto;">
                    <div class="col-md-12 text-center text-muted" style="padding:30px;">Loading media...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    window._cmsMediaPickerMode = 'featured';
    window._cmsMediaPickerCallback = null;

    function openMediaPicker(mode, callback) {
        window._cmsMediaPickerMode = mode || 'featured';
        window._cmsMediaPickerCallback = typeof callback === 'function' ? callback : null;

        var heading = document.getElementById('mediaPickerHeading');
        if (heading) {
            heading.textContent = (window._cmsMediaPickerMode === 'editor' || window._cmsMediaPickerMode === 'tinymce_cb')
                ? 'Insert Image into Content'
                : 'Select Featured Image from Media Library';
        }

        jQuery('#cmsMediaPickerModal').modal('show');
        loadMediaPicker();
    }

    function escapeAttr(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function loadMediaPicker() {
        var q = document.getElementById('mediaPickerSearch').value || '';
        var grid = document.getElementById('mediaPickerGrid');
        jQuery.get('<?php echo e(route("extensionsvalley.cms.media.picker")); ?>', { q: q, images_only: 1 }, function (res) {
            if (!res.data || res.data.length === 0) {
                grid.innerHTML = '<div class="col-md-12 text-center text-muted" style="padding:30px;">No images found in Media Library. Upload images in CMS &rarr; Media Library first.</div>';
                return;
            }
            var html = '';
            res.data.forEach(function (item) {
                html += '<div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom:14px;">'
                    + '<div class="thumbnail cms-media-tile" style="margin-bottom:0; cursor:pointer; border-radius:6px; overflow:hidden;"'
                    + ' data-path="' + escapeAttr(item.file_path) + '"'
                    + ' data-url="' + escapeAttr(item.url) + '"'
                    + ' data-alt="' + escapeAttr(item.alt_text || item.title) + '">'
                    + '<div style="height:110px; display:flex; align-items:center; justify-content:center; background:#f8fafc; overflow:hidden;">'
                    + '<img src="' + escapeAttr(item.url) + '" alt="' + escapeAttr(item.alt_text || item.title) + '" style="max-height:110px; max-width:100%; object-fit:cover;">'
                    + '</div>'
                    + '<div class="caption" style="padding:8px;">'
                    + '<div style="font-size:12px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:6px;" title="' + escapeAttr(item.title) + '">' + escapeAttr(item.title) + '</div>'
                    + '<button type="button" class="btn btn-primary btn-xs btn-block cms-media-select-btn"><i class="fa fa-check"></i> Select</button>'
                    + '</div></div></div>';
            });
            grid.innerHTML = html;
        });
    }

    jQuery(document).on('click', '.cms-media-tile, .cms-media-select-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var tile = jQuery(this).closest('.cms-media-tile');
        var filePath = tile.attr('data-path');
        var fullUrl = tile.attr('data-url');
        var altText = tile.attr('data-alt') || '';

        if (window._cmsMediaPickerMode === 'tinymce_cb' && window._cmsMediaPickerCallback) {
            window._cmsMediaPickerCallback(fullUrl, { alt: altText });
        } else if (window._cmsMediaPickerMode === 'editor') {
            var imgTag = '<img src="' + escapeAttr(fullUrl) + '" alt="' + escapeAttr(altText) + '" style="max-width:100%;height:auto;" />';
            if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
                tinymce.activeEditor.insertContent(imgTag);
            } else {
                var ta = document.getElementById('content');
                if (ta) ta.value += '\n' + imgTag;
            }
        } else {
            var hidden = document.getElementById('selected_media_path');
            var preview = document.getElementById('featuredImagePreviewBox');
            var removeCb = document.querySelector('input[name="remove_featured_image"]');
            if (hidden) hidden.value = filePath;
            if (removeCb) removeCb.checked = false;
            if (preview) {
                preview.innerHTML = '<img src="' + escapeAttr(fullUrl) + '" alt="' + escapeAttr(altText) + '" class="img-responsive img-thumbnail" style="max-height:180px;">';
            }
        }

        jQuery('#cmsMediaPickerModal').modal('hide');
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Cms/src/Views/admin/partials/mediapicker.blade.php ENDPATH**/ ?>