<div class="panel panel-default" id="cmsSeoScoreBox" style="margin-top:12px;">
    <div class="panel-heading" style="display:flex; justify-content:space-between; align-items:center;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span id="seoScoreDot" style="display:inline-block; width:14px; height:14px; border-radius:50%; background:#94a3b8; box-shadow:0 0 0 3px rgba(148,163,184,0.2);"></span>
            <strong>SEO Analysis</strong>
        </div>
        <span id="seoScoreBadge" class="label label-default">Not Set (0/100)</span>
    </div>
    <div class="panel-body" style="padding:12px 15px;">
        <ul id="seoScoreChecks" style="list-style:none; padding:0; margin:0; font-size:12.5px;"></ul>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        function getEditorContent() {
            if (typeof tinymce !== 'undefined') {
                var ed = tinymce.get('content') || tinymce.activeEditor;
                if (ed && typeof ed.getContent === 'function') {
                    return ed.getContent({ format: 'text' }) || '';
                }
            }
            var ta = document.getElementById('content');
            return ta ? (ta.value || '').replace(/<[^>]+>/g, ' ') : '';
        }

        function evaluateSeo() {
            var title = (document.getElementById('title') ? document.getElementById('title').value : '').trim();
            var metaTitle = (document.getElementById('meta_title') ? document.getElementById('meta_title').value : '').trim();
            var metaKeywords = (document.getElementById('meta_keywords') ? document.getElementById('meta_keywords').value : '').trim();
            var metaDesc = (document.getElementById('meta_description') ? document.getElementById('meta_description').value : '').trim();
            var contentText = getEditorContent().trim();
            var hasImage = !!(
                (document.getElementById('selected_media_path') && document.getElementById('selected_media_path').value) ||
                (document.getElementById('featuredImagePreviewBox') && document.getElementById('featuredImagePreviewBox').querySelector('img')) ||
                (document.getElementById('featured_image') && document.getElementById('featured_image').files && document.getElementById('featured_image').files.length > 0)
            );
            var slug = (document.getElementById('slug') ? document.getElementById('slug').value : '').trim();

            var checks = [];
            var score = 0;
            var color = '#94a3b8';
            var label = 'Not Set';
            var badgeClass = 'label-default';

            if (!metaTitle && !metaKeywords && !metaDesc) {
                checks.push({ c: '#94a3b8', t: 'Add Meta Title, Meta Keywords, and Meta Description to compute SEO score.' });
            } else {
                var effTitle = metaTitle || title;
                if (metaTitle) {
                    if (effTitle.length >= 30 && effTitle.length <= 65) {
                        score += 25;
                        checks.push({ c: '#22c55e', t: 'Meta Title length is optimal (' + effTitle.length + ' chars).' });
                    } else {
                        score += 15;
                        checks.push({ c: '#f59e0b', t: 'Meta Title is ' + effTitle.length + ' chars (aim for 30–65 chars).' });
                    }
                } else {
                    checks.push({ c: '#ef4444', t: 'Meta Title is missing.' });
                }

                if (metaDesc) {
                    if (metaDesc.length >= 110 && metaDesc.length <= 165) {
                        score += 25;
                        checks.push({ c: '#22c55e', t: 'Meta Description length is optimal (' + metaDesc.length + ' chars).' });
                    } else {
                        score += 15;
                        checks.push({ c: '#f59e0b', t: 'Meta Description is ' + metaDesc.length + ' chars (aim for 110–165 chars).' });
                    }
                } else {
                    checks.push({ c: '#ef4444', t: 'Meta Description is missing.' });
                }

                if (metaKeywords) {
                    score += 10;
                    var primaryKw = metaKeywords.split(',')[0].trim().toLowerCase();
                    if (primaryKw) {
                        if (effTitle.toLowerCase().indexOf(primaryKw) !== -1) {
                            score += 8;
                            checks.push({ c: '#22c55e', t: 'Primary keyword "' + primaryKw + '" found in Title.' });
                        } else {
                            checks.push({ c: '#f59e0b', t: 'Include primary keyword "' + primaryKw + '" in your Meta Title.' });
                        }
                        if ((metaDesc + ' ' + contentText).toLowerCase().indexOf(primaryKw) !== -1) {
                            score += 7;
                            checks.push({ c: '#22c55e', t: 'Primary keyword "' + primaryKw + '" found in Description/Content.' });
                        } else {
                            checks.push({ c: '#f59e0b', t: 'Include primary keyword "' + primaryKw + '" in Meta Description or Content.' });
                        }
                    }
                } else {
                    checks.push({ c: '#ef4444', t: 'Meta Keywords are missing.' });
                }

                var words = contentText ? contentText.split(/\s+/).filter(Boolean).length : 0;
                if (words >= 100) {
                    score += 15;
                    checks.push({ c: '#22c55e', t: 'Good content depth (' + words + ' words).' });
                } else if (words >= 25) {
                    score += 8;
                    checks.push({ c: '#f59e0b', t: 'Content has ' + words + ' words (100+ recommended).' });
                } else {
                    checks.push({ c: '#ef4444', t: 'Content is very short (' + words + ' words).' });
                }

                if (hasImage) {
                    score += 5;
                    checks.push({ c: '#22c55e', t: 'Featured image is set.' });
                } else {
                    checks.push({ c: '#f59e0b', t: 'Add a Featured Image for better social sharing.' });
                }

                if (slug || title) score += 5;
                score = Math.min(100, score);

                if (score >= 75) {
                    color = '#22c55e'; label = 'Good'; badgeClass = 'label-success';
                } else if (score >= 45) {
                    color = '#f59e0b'; label = 'Needs Improvement'; badgeClass = 'label-warning';
                } else {
                    color = '#ef4444'; label = 'Poor'; badgeClass = 'label-danger';
                }
            }

            var dot = document.getElementById('seoScoreDot');
            var badge = document.getElementById('seoScoreBadge');
            var list = document.getElementById('seoScoreChecks');
            if (dot) dot.style.background = color;
            if (badge) {
                badge.className = 'label ' + badgeClass;
                badge.textContent = label + ' (' + score + '/100)';
            }
            if (list) {
                list.innerHTML = checks.map(function (item) {
                    return '<li style="margin-bottom:5px; display:flex; align-items:center; gap:8px;">'
                        + '<span style="display:inline-block;width:9px;height:9px;border-radius:50%;flex-shrink:0;background:' + item.c + ';"></span>'
                        + '<span>' + item.t + '</span></li>';
                }).join('');
            }
        }

        jQuery(document).ready(function () {
            ['title', 'slug', 'meta_title', 'meta_keywords', 'meta_description', 'content', 'featured_image'].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', evaluateSeo);
                    el.addEventListener('change', evaluateSeo);
                }
            });
            evaluateSeo();
            setInterval(evaluateSeo, 2000);
        });
    })();
</script>
@endpush

