<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsPostsTable extends BaseTable
{
    public $page_title = 'Manage Posts';
    public $table_name = 'cms_posts';
    public $acl_key = 'extensionsvalley.cms.posts';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\Post';

    public $listable = [
        'title' => 'Title',
        'language_code' => 'Lang',
        'category_name' => 'Category',
        'published_at' => 'Publish Date',
        'status' => 'Status',
        'created_at' => 'Created',
    ];

    public $show_toolbar = [
        'view' => 'Show',
        'add' => 'Add',
        'edit' => 'Edit',
        'publish' => 'Publish',
        'unpublish' => 'Unpublish',
        'trash' => 'Trash',
        'restore' => 'Restore',
        'forcedelete' => 'Force Delete',
    ];

    public $routes = [
        'add_route' => 'cms/addpost',
        'edit_route' => 'cms/editpost',
        'view_route' => 'cms/viewpost',
    ];

    public $advanced_filter = [
        'layout' => '',
        'filters' => [
            'filter_trashed' => 'filter_trashed',
        ],
    ];

    public function getQuery($request)
    {
        $filter_trashed = $request->get('filter_trashed');

        $query = DB::table('cms_posts as p')
            ->leftJoin('cms_categories as c', 'p.category_id', '=', 'c.id')
            ->select('p.id', 'p.title', 'p.language_code', 'c.name as category_name', 'p.published_at', 'p.status', 'p.created_at');

        if ($filter_trashed == 1) {
            $query->whereNotNull('p.deleted_at');
        } else {
            $query->whereNull('p.deleted_at');
        }

        return datatables()->of($query)
            ->filter(function ($q) use ($request) {
                $kw = trim((string) ($request->input('search.value') ?? $request->get('customsearch', '')));
                if ($kw !== '') {
                    $q->where(function ($sub) use ($kw) {
                        $sub->where('p.title', 'like', "%{$kw}%")
                            ->orWhere('p.slug', 'like', "%{$kw}%")
                            ->orWhere('p.language_code', 'like', "%{$kw}%")
                            ->orWhere('c.name', 'like', "%{$kw}%");
                    });
                }
            })
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('language_code', fn ($row) => '<span class="label label-primary">' . strtoupper(e($row->language_code ?? 'en')) . '</span>')
            ->editColumn('category_name', fn ($row) => e($row->category_name ?: 'Uncategorized'))
            ->editColumn('published_at', fn ($row) => $row->published_at ? date('M j, Y H:i', strtotime($row->published_at)) : 'Immediate')
            ->editColumn('status', function ($row) {
                if ((int) $row->status === 1 && $row->published_at && strtotime($row->published_at) > time()) {
                    return '<span class="label label-info">Scheduled</span>';
                }
                return (int) $row->status === 1
                    ? '<span class="label label-success">Published</span>'
                    : '<span class="label label-default">Draft</span>';
            })
            ->editColumn('created_at', fn ($row) => $row->created_at ? date('M-j-Y', strtotime($row->created_at)) : '')
            ->rawColumns(['sl', 'language_code', 'status'])
            ->make(true);
    }
}

