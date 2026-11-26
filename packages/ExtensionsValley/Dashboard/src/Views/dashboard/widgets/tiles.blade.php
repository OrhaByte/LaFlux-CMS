@php
    $canAcl = function (string $key, string $action = 'view'): bool {
        if (class_exists(\ExtensionsValley\Cms\Helpers\CmsHelper::class)) {
            return \ExtensionsValley\Cms\Helpers\CmsHelper::hasAcl($key, $action);
        }
        $user = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        if (!$user) return false;
        if ((int) $user->groups === 1) return true;
        return \Illuminate\Support\Facades\DB::table('acl_permission')
            ->where('group_id', $user->groups)
            ->where('acl_key', $key)
            ->where($action, 1)
            ->exists();
    };

    $tiles = [];

    if ($canAcl('extensionsvalley.dashboard.users', 'view')) {
        $totalUsers = \Illuminate\Support\Facades\DB::table('users')->whereNull('deleted_at')->count();
        $activeUsers = \Illuminate\Support\Facades\DB::table('users')->whereNull('deleted_at')->where('status', 1)->count();
        $tiles[] = [
            'label' => 'Total Users',
            'count' => number_format($totalUsers),
            'sub' => number_format($activeUsers) . ' Active',
            'icon' => 'fa-users',
            'accent' => '#4f46e5',
            'bg' => '#eef2ff',
            'url' => url('admin/ExtensionsValley/dashboard/list/users'),
        ];
    }

    if ($canAcl('extensionsvalley.cms.posts', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_posts')) {
        $totalPosts = \Illuminate\Support\Facades\DB::table('cms_posts')->whereNull('deleted_at')->count();
        $publishedPosts = \Illuminate\Support\Facades\DB::table('cms_posts')->whereNull('deleted_at')->where('status', 1)->count();
        $tiles[] = [
            'label' => 'Total Posts',
            'count' => number_format($totalPosts),
            'sub' => number_format($publishedPosts) . ' Published',
            'icon' => 'fa-file-text-o',
            'accent' => '#2563eb',
            'bg' => '#eff6ff',
            'url' => url('admin/ExtensionsValley/cms/list/cms_posts'),
        ];
    }

    if ($canAcl('extensionsvalley.cms.pages', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_pages')) {
        $totalPages = \Illuminate\Support\Facades\DB::table('cms_pages')->whereNull('deleted_at')->count();
        $publishedPages = \Illuminate\Support\Facades\DB::table('cms_pages')->whereNull('deleted_at')->where('status', 1)->count();
        $tiles[] = [
            'label' => 'Total Pages',
            'count' => number_format($totalPages),
            'sub' => number_format($publishedPages) . ' Published',
            'icon' => 'fa-files-o',
            'accent' => '#059669',
            'bg' => '#ecfdf5',
            'url' => url('admin/ExtensionsValley/cms/list/cms_pages'),
        ];
    }

    if ($canAcl('extensionsvalley.cms.categories', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_categories')) {
        $totalCategories = \Illuminate\Support\Facades\DB::table('cms_categories')->whereNull('deleted_at')->count();
        $activeCategories = \Illuminate\Support\Facades\DB::table('cms_categories')->whereNull('deleted_at')->where('status', 1)->count();
        $tiles[] = [
            'label' => 'Categories',
            'count' => number_format($totalCategories),
            'sub' => number_format($activeCategories) . ' Active',
            'icon' => 'fa-folder-open-o',
            'accent' => '#d97706',
            'bg' => '#fffbeb',
            'url' => url('admin/ExtensionsValley/cms/list/cms_categories'),
        ];
    }

    if ($canAcl('extensionsvalley.cms.tags', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_tags')) {
        $totalTags = \Illuminate\Support\Facades\DB::table('cms_tags')->whereNull('deleted_at')->count();
        $tiles[] = [
            'label' => 'Tags',
            'count' => number_format($totalTags),
            'sub' => 'Post Taxonomy',
            'icon' => 'fa-tags',
            'accent' => '#7c3aed',
            'bg' => '#f5f3ff',
            'url' => url('admin/ExtensionsValley/cms/list/cms_tags'),
        ];
    }

    if ($canAcl('extensionsvalley.cms.menus', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_menus')) {
        $totalMenus = \Illuminate\Support\Facades\DB::table('cms_menus')->whereNull('deleted_at')->count();
        $totalMenuItems = \Illuminate\Support\Facades\Schema::hasTable('cms_menu_items')
            ? \Illuminate\Support\Facades\DB::table('cms_menu_items')->count()
            : 0;
        $tiles[] = [
            'label' => 'Menus',
            'count' => number_format($totalMenus),
            'sub' => number_format($totalMenuItems) . ' Links',
            'icon' => 'fa-bars',
            'accent' => '#0891b2',
            'bg' => '#ecfeff',
            'url' => url('admin/ExtensionsValley/cms/list/cms_menus'),
        ];
    }
@endphp

@if(!empty($tiles))
<style>
    .lf-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
        gap: 16px;
        margin: 8px 0 22px 0;
        padding: 0 10px;
    }
    .lf-kpi-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px 20px;
        text-decoration: none !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
        transition: transform 0.16s ease, box-shadow 0.16s ease, border-color 0.16s ease;
    }
    .lf-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .lf-kpi-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 6px;
    }
    .lf-kpi-value {
        font-size: 28px;
        font-weight: 800;
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 6px;
    }
    .lf-kpi-sub {
        font-size: 12px;
        font-weight: 600;
    }
    .lf-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
</style>

<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0;">
    <div class="lf-kpi-grid">
        @foreach($tiles as $tile)
            <a href="{{ $tile['url'] }}" class="lf-kpi-card">
                <div>
                    <div class="lf-kpi-label">{{ $tile['label'] }}</div>
                    <div class="lf-kpi-value">{{ $tile['count'] }}</div>
                    <div class="lf-kpi-sub" style="color: {{ $tile['accent'] }};">{{ $tile['sub'] }}</div>
                </div>
                <div class="lf-kpi-icon" style="background: {{ $tile['bg'] }}; color: {{ $tile['accent'] }};">
                    <i class="fa {{ $tile['icon'] }}"></i>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif
