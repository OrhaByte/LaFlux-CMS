<aside>
    <div class="lf-sidebar-card">
        <h3>Categories</h3>
        @if(!empty($sidebarCategories) && $sidebarCategories->isNotEmpty())
            <ul class="lf-sidebar-list">
                @foreach($sidebarCategories as $cat)
                    <li>
                        <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $cat->slug) }}">{{ $cat->name }}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="lf-meta" style="margin:0;">No categories yet.</p>
        @endif
    </div>

    <div class="lf-sidebar-card">
        <h3>Tags</h3>
        @if(!empty($sidebarTags) && $sidebarTags->isNotEmpty())
            <div>
                @foreach($sidebarTags as $t)
                    <a class="lf-pill" href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('tag/' . $t->slug) }}">#{{ $t->name }}</a>
                @endforeach
            </div>
        @else
            <p class="lf-meta" style="margin:0;">No tags yet.</p>
        @endif
    </div>
</aside>

