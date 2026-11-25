@if(isset($versions))
    <div class="panel panel-default">
        <div class="panel-heading" style="display:flex; justify-content:space-between; align-items:center;">
            <strong><i class="fa fa-history"></i> Versions</strong>
            <span class="badge">{{ $versions->count() }}</span>
        </div>
        <div class="panel-body" style="padding:10px 12px;">
            <div id="cmsVersionAppliedAlert" class="alert alert-success" style="display:none; padding:8px 10px; font-size:12px; margin-bottom:10px;"></div>

            @if($versions->isEmpty())
                <p class="text-muted" style="margin:0; font-size:12.5px;">No published versions yet. A snapshot is saved each time you publish.</p>
            @else
                <div style="max-height: 250px; overflow-y: auto; padding-right: 4px;">
                    @foreach($versions as $ver)
                        <div style="border-bottom:1px solid #eef2f7; padding:8px 0; display:flex; justify-content:space-between; align-items:center; gap:8px;">
                            <div style="min-width:0;">
                                <div>
                                    <span class="label label-primary">v{{ $ver->version_number }}</span>
                                    <small class="text-muted" style="margin-left:4px;">{{ $ver->created_at ? $ver->created_at->format('M j, Y H:i') : '' }}</small>
                                </div>
                                <div style="font-size:12px; color:#475569; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:2px;" title="{{ $ver->title }}">
                                    {{ $ver->title }}
                                </div>
                                @if($ver->author)
                                    <small class="text-muted" style="font-size:11px;">by {{ $ver->author->name }}</small>
                                @endif
                            </div>
                            <div style="white-space:nowrap;">
                                <button type="button" class="btn btn-default btn-xs" onclick="viewCmsVersion({{ $ver->id }})" title="Preview version">
                                    <i class="fa fa-eye"></i> View
                                </button>
                                @if(!isset($viewmode))
                                    <button type="button" class="btn btn-warning btn-xs" onclick="applyCmsVersion({{ $ver->id }})" title="Apply this version to editor">
                                        <i class="fa fa-undo"></i> Apply
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Version Preview Modal --}}
    <div class="modal fade" id="cmsVersionPreviewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-history"></i> <span id="vpHeading">Version Preview</span></h4>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size:12.5px; margin-bottom:12px;" id="vpMeta"></p>
                    <div style="margin-bottom:12px;">
                        <strong>Title:</strong> <span id="vpTitle"></span>
                    </div>
                    <div id="vpExcerptWrap" style="display:none; margin-bottom:12px;">
                        <strong>Excerpt:</strong>
                        <p id="vpExcerpt" style="margin:4px 0 0 0; color:#475569;"></p>
                    </div>
                    <div style="margin-bottom:12px;">
                        <strong>Content:</strong>
                        <div id="vpContent" style="border:1px solid #e2e8f0; border-radius:6px; padding:16px; background:#f8fafc; max-height:360px; overflow-y:auto; margin-top:6px;"></div>
                    </div>
                    <div id="vpSeoWrap" style="font-size:12.5px; color:#475569; border-top:1px solid #e2e8f0; padding-top:10px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @if(!isset($viewmode))
                        <button type="button" class="btn btn-warning" id="vpApplyBtn">
                            <i class="fa fa-undo"></i> Apply This Version
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Overwrite Confirmation Modal --}}
    <div class="modal fade" id="cmsVersionConfirmModal" tabindex="-1" role="dialog" style="z-index: 1060;">
        <div class="modal-dialog modal-sm" role="document" style="max-width:440px; width:92%; margin-top:110px;">
            <div class="modal-content" style="border-radius:12px; overflow:hidden; border:0; box-shadow:0 20px 50px rgba(15,23,42,0.28);">
                <div class="modal-body" style="padding:26px 24px 18px; text-align:center;">
                    <div style="width:54px; height:54px; margin:0 auto 14px; border-radius:50%; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; font-size:24px;">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <h4 style="margin:0 0 8px 0; font-weight:700; color:#0f172a;" id="vcHeading">Apply Version?</h4>
                    <p style="margin:0 0 14px 0; font-size:13.5px; color:#475569; line-height:1.55;">
                        This will <strong>overwrite</strong> the current Title, Content, Featured Image, and SEO fields in the editor.
                    </p>
                    <div id="vcSummary" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 12px; font-size:12.5px; color:#334155; text-align:left; margin-bottom:10px;"></div>
                    <p style="margin:0; font-size:12px; color:#64748b;">
                        Nothing is published live until you click <strong>Save</strong>.
                    </p>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9; background:#f8fafc; padding:12px 20px; display:flex; justify-content:flex-end; gap:8px;">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" style="border-radius:6px; padding:7px 16px;">Cancel</button>
                    <button type="button" class="btn btn-warning btn-sm" id="vcConfirmBtn" style="border-radius:6px; padding:7px 16px; font-weight:600;">
                        <i class="fa fa-undo"></i> Yes, Overwrite &amp; Apply
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $versionsPayload = $versions->keyBy('id')->map(function ($v) {
            return [
                'id' => $v->id,
                'version_number' => $v->version_number,
                'title' => $v->title ?? '',
                'excerpt' => $v->excerpt ?? '',
                'content' => $v->content ?? '',
                'featured_image' => $v->featured_image ?? '',
                'featured_image_url' => $v->featured_image
                    ? (str_starts_with($v->featured_image, 'http') ? $v->featured_image : asset($v->featured_image))
                    : '',
                'meta_title' => $v->meta_title ?? '',
                'meta_keywords' => $v->meta_keywords ?? '',
                'meta_description' => $v->meta_description ?? '',
                'author' => $v->author->name ?? 'Admin',
                'created_at' => $v->created_at ? $v->created_at->format('M j, Y H:i') : '',
            ];
        });
    @endphp
    @push('scripts')
    <script>
        window._cmsVersionsMap = @json($versionsPayload);

        function viewCmsVersion(id) {
            var v = window._cmsVersionsMap && window._cmsVersionsMap[id];
            if (!v) return;

            document.getElementById('vpHeading').textContent = 'Version v' + v.version_number + ' Preview';
            document.getElementById('vpMeta').textContent = 'Saved on ' + v.created_at + ' by ' + v.author;
            document.getElementById('vpTitle').textContent = v.title;

            var exWrap = document.getElementById('vpExcerptWrap');
            if (v.excerpt) {
                exWrap.style.display = 'block';
                document.getElementById('vpExcerpt').textContent = v.excerpt;
            } else {
                exWrap.style.display = 'none';
            }

            document.getElementById('vpContent').innerHTML = v.content || '<em class="text-muted">(Empty content)</em>';

            var seoParts = [];
            if (v.meta_title) seoParts.push('<strong>Meta Title:</strong> ' + v.meta_title);
            if (v.meta_keywords) seoParts.push('<strong>Meta Keywords:</strong> ' + v.meta_keywords);
            if (v.meta_description) seoParts.push('<strong>Meta Description:</strong> ' + v.meta_description);
            document.getElementById('vpSeoWrap').innerHTML = seoParts.join('<br>');

            var applyBtn = document.getElementById('vpApplyBtn');
            if (applyBtn) {
                applyBtn.onclick = function () {
                    jQuery('#cmsVersionPreviewModal').modal('hide');
                    applyCmsVersion(id);
                };
            }

            jQuery('#cmsVersionPreviewModal').modal('show');
        }

        function applyCmsVersion(id) {
            var v = window._cmsVersionsMap && window._cmsVersionsMap[id];
            if (!v) return false;

            document.getElementById('vcHeading').textContent = 'Apply Version v' + v.version_number + '?';
            document.getElementById('vcSummary').innerHTML =
                '<div><strong>Title:</strong> ' + jQuery('<div>').text(v.title).html() + '</div>' +
                '<div style="margin-top:3px; color:#64748b;"><strong>Saved:</strong> ' + jQuery('<div>').text(v.created_at + ' by ' + v.author).html() + '</div>';

            var confirmBtn = document.getElementById('vcConfirmBtn');
            confirmBtn.onclick = function () {
                executeApplyCmsVersion(v);
                jQuery('#cmsVersionConfirmModal').modal('hide');
            };

            jQuery('#cmsVersionConfirmModal').modal('show');
            return true;
        }

        function executeApplyCmsVersion(v) {
            var titleEl = document.getElementById('title');
            if (titleEl) titleEl.value = v.title;

            var excerptEl = document.getElementById('excerpt');
            if (excerptEl) excerptEl.value = v.excerpt;

            var contentEl = document.getElementById('content');
            if (contentEl) contentEl.value = v.content;
            if (typeof tinymce !== 'undefined') {
                var ed = tinymce.get('content') || tinymce.activeEditor;
                if (ed) ed.setContent(v.content || '');
            }

            var mt = document.getElementById('meta_title');
            if (mt) mt.value = v.meta_title;
            var mk = document.getElementById('meta_keywords');
            if (mk) mk.value = v.meta_keywords;
            var md = document.getElementById('meta_description');
            if (md) md.value = v.meta_description;

            if (v.featured_image) {
                var hidden = document.getElementById('selected_media_path');
                var preview = document.getElementById('featuredImagePreviewBox');
                var removeCb = document.querySelector('input[name="remove_featured_image"]');
                if (hidden) hidden.value = v.featured_image;
                if (removeCb) removeCb.checked = false;
                if (preview && v.featured_image_url) {
                    preview.innerHTML = '<img src="' + v.featured_image_url + '" alt="Featured Image" class="img-responsive img-thumbnail" style="max-height:180px;">';
                }
            }

            var alertBox = document.getElementById('cmsVersionAppliedAlert');
            if (alertBox) {
                alertBox.innerHTML = '<i class="fa fa-check-circle"></i> Version <strong>v' + v.version_number + '</strong> loaded into editor. Click <strong>Save</strong> to publish.';
                alertBox.style.display = 'block';
            }

            return true;
        }
    </script>
    @endpush
@endif
