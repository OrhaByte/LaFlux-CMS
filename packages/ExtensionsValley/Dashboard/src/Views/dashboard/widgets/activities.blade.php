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

    $canViewPosts = $canAcl('extensionsvalley.cms.posts', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_posts');
    $canAddPosts = $canAcl('extensionsvalley.cms.posts', 'adding');
    $canEditPosts = $canAcl('extensionsvalley.cms.posts', 'edit');

    $canViewPages = $canAcl('extensionsvalley.cms.pages', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_pages');
    $canAddPages = $canAcl('extensionsvalley.cms.pages', 'adding');
    $canEditPages = $canAcl('extensionsvalley.cms.pages', 'edit');

    $canViewCategories = $canAcl('extensionsvalley.cms.categories', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_categories');
    $canViewMenus = $canAcl('extensionsvalley.cms.menus', 'view') && \Illuminate\Support\Facades\Schema::hasTable('cms_menus');
    $canViewMedia = $canAcl('extensionsvalley.cms.media', 'view');
    $canViewCustomizer = $canAcl('extensionsvalley.cms.customizer', 'view');
    $canViewUsers = $canAcl('extensionsvalley.dashboard.users', 'view');

    $recentPosts = $canViewPosts
        ? \Illuminate\Support\Facades\DB::table('cms_posts as p')
            ->leftJoin('cms_categories as c', 'p.category_id', '=', 'c.id')
            ->select('p.id', 'p.title', 'p.slug', 'p.status', 'p.published_at', 'p.created_at', 'c.name as category_name')
            ->whereNull('p.deleted_at')
            ->orderByDesc('p.created_at')
            ->limit(6)
            ->get()
        : collect();

    $recentPages = $canViewPages
        ? \Illuminate\Support\Facades\DB::table('cms_pages')
            ->select('id', 'title', 'slug', 'status', 'published_at', 'updated_at')
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get()
        : collect();

    $showCategoryStats = $canViewCategories && $canViewPosts;
    $categoryStats = $showCategoryStats
        ? \Illuminate\Support\Facades\DB::table('cms_categories as c')
            ->leftJoin('cms_posts as p', function ($join) {
                $join->on('c.id', '=', 'p.category_id')->whereNull('p.deleted_at');
            })
            ->select('c.id', 'c.name', \Illuminate\Support\Facades\DB::raw('COUNT(p.id) as posts_count'))
            ->whereNull('c.deleted_at')
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('posts_count')
            ->limit(6)
            ->get()
        : collect();

    $maxCatPosts = max(1, (int) $categoryStats->max('posts_count'));

    $recentUsers = $canViewUsers
        ? \Illuminate\Support\Facades\DB::table('users as u')
            ->leftJoin('groups as g', 'u.groups', '=', 'g.id')
            ->select('u.id', 'u.name', 'u.email', 'u.status', 'u.created_at', 'g.name as group_name')
            ->whereNull('u.deleted_at')
            ->orderByDesc('u.created_at')
            ->limit(5)
            ->get()
        : collect();

    $postToken = base64_encode('extensionsvalley.cms.posts');
    $pageToken = base64_encode('extensionsvalley.cms.pages');
    $hasAnyWidget = $canViewPosts || $canViewPages || $canViewUsers;
@endphp

<div class="col-md-12 col-sm-12 col-xs-12">
    {{-- Quick Actions (only permitted actions are shown) --}}
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Quick Actions <small>{{ \WebConf::get('site_name') ?: 'LaFlux CMS' }}</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if($canAddPosts)
                        <a href="{{ route('extensionsvalley.cms.addpost', ['accesstoken' => $postToken]) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> New Post
                        </a>
                    @endif
                    @if($canAddPages)
                        <a href="{{ route('extensionsvalley.cms.addpage', ['accesstoken' => $pageToken]) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-file-o"></i> New Page
                        </a>
                    @endif
                    @if($canViewCategories)
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_categories') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-folder-open"></i> Categories
                        </a>
                    @endif
                    @if($canViewMenus)
                        <a href="{{ url('admin/ExtensionsValley/cms/list/cms_menus') }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-bars"></i> Menus
                        </a>
                    @endif
                    @if($canViewMedia)
                        <a href="{{ route('extensionsvalley.cms.media') }}" class="btn btn-dark btn-sm">
                            <i class="fa fa-picture-o"></i> Media Library
                        </a>
                    @endif
                    @if($canViewCustomizer)
                        <a href="{{ route('extensionsvalley.cms.customizer') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-paint-brush"></i> Theme Customizer
                        </a>
                    @endif
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-default btn-sm">
                        <i class="fa fa-external-link"></i> View Live Site
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($canViewPosts || $showCategoryStats)
    <div class="row">
        @if($canViewPosts)
            <div class="{{ $showCategoryStats ? 'col-md-8' : 'col-md-12' }} col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Recent Posts <small>Latest articles</small></h2>
                        <div class="pull-right">
                            <a href="{{ url('admin/ExtensionsValley/cms/list/cms_posts') }}" class="btn btn-xs btn-default">View All</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        @if($recentPosts->isEmpty())
                            <p class="text-muted">No posts yet. Create your first post to see it here.</p>
                        @else
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Publish Date</th>
                                    @if($canEditPosts)
                                        <th width="80">Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentPosts as $post)
                                    <tr>
                                        <td><strong>{{ $post->title }}</strong></td>
                                        <td>{{ $post->category_name ?: 'Uncategorized' }}</td>
                                        <td>
                                            @if((int)$post->status === 1 && $post->published_at && strtotime($post->published_at) > time())
                                                <span class="label label-info">Scheduled</span>
                                            @elseif((int)$post->status === 1)
                                                <span class="label label-success">Published</span>
                                            @else
                                                <span class="label label-default">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->published_at ? date('M j, Y', strtotime($post->published_at)) : date('M j, Y', strtotime($post->created_at)) }}</td>
                                        @if($canEditPosts)
                                            <td>
                                                <a href="{{ route('extensionsvalley.cms.editpost', ['id' => $post->id, 'accesstoken' => $postToken]) }}" class="btn btn-xs btn-primary">Edit</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($showCategoryStats)
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Posts by Category</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        @forelse($categoryStats as $cat)
                            @php $pct = round(($cat->posts_count / $maxCatPosts) * 100); @endphp
                            <div style="margin-bottom: 12px;">
                                <div style="display:flex; justify-content:space-between;">
                                    <span>{{ $cat->name }}</span>
                                    <strong>{{ $cat->posts_count }}</strong>
                                </div>
                                <div class="progress progress_sm" style="margin-top:4px; margin-bottom:0;">
                                    <div class="progress-bar bg-green" role="progressbar" style="width: {{ max($pct, 6) }}%;"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No categories created yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif

    @if($canViewPages || $canViewUsers)
    <div class="row">
        @if($canViewPages)
            <div class="{{ $canViewUsers ? 'col-md-6' : 'col-md-12' }} col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Recent Pages</h2>
                        <div class="pull-right">
                            <a href="{{ url('admin/ExtensionsValley/cms/list/cms_pages') }}" class="btn btn-xs btn-default">View All</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        @if($recentPages->isEmpty())
                            <p class="text-muted">No pages yet.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Page Title</th>
                                    <th>Status</th>
                                    @if($canEditPages)
                                        <th width="80">Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentPages as $page)
                                    <tr>
                                        <td>
                                            <strong>{{ $page->title }}</strong><br/>
                                            <small class="text-muted">/page/{{ $page->slug }}</small>
                                        </td>
                                        <td>
                                            @if((int)$page->status === 1 && $page->published_at && strtotime($page->published_at) > time())
                                                <span class="label label-info">Scheduled</span>
                                            @elseif((int)$page->status === 1)
                                                <span class="label label-success">Published</span>
                                            @else
                                                <span class="label label-default">Draft</span>
                                            @endif
                                        </td>
                                        @if($canEditPages)
                                            <td>
                                                <a href="{{ route('extensionsvalley.cms.editpage', ['id' => $page->id, 'accesstoken' => $pageToken]) }}" class="btn btn-xs btn-primary">Edit</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($canViewUsers)
            <div class="{{ $canViewPages ? 'col-md-6' : 'col-md-12' }} col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Recent Users</h2>
                        <div class="pull-right">
                            <a href="{{ url('admin/ExtensionsValley/dashboard/list/users') }}" class="btn btn-xs btn-default">Manage Users</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recentUsers as $usr)
                                <tr>
                                    <td>
                                        <strong>{{ $usr->name }}</strong><br/>
                                        <small class="text-muted">{{ $usr->email }}</small>
                                    </td>
                                    <td>{{ $usr->group_name ?: 'User' }}</td>
                                    <td>
                                        @if((int)$usr->status === 1)
                                            <span class="label label-success">Active</span>
                                        @else
                                            <span class="label label-default">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif

    @if(!$hasAnyWidget)
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_content" style="padding:28px; text-align:center;">
                        <h3 style="margin-top:0;">Welcome, {{ \Auth::guard('admin')->user()->name ?? 'User' }}!</h3>
                        <p class="text-muted" style="margin:0;">Use the navigation menu on the left to access the sections assigned to your role.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
