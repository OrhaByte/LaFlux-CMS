<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsCategoriesTable extends BaseTable
{
    public $page_title = 'Manage Categories';
    public $table_name = 'cms_categories';
    public $acl_key = 'extensionsvalley.cms.categories';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\Category';

    public $listable = [
        'name' => 'Name',
        'language_code' => 'Lang',
        'slug' => 'Slug',
        'parent_name' => 'Parent',
        'status' => 'Status',
        'created_at' => 'Date',
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
        'add_route' => 'cms/addcategory',
        'edit_route' => 'cms/editcategory',
        'view_route' => 'cms/viewcategory',
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

        $query = DB::table('cms_categories as c')
            ->leftJoin('cms_categories as p', 'c.parent_id', '=', 'p.id')
            ->select('c.id', 'c.name', 'c.language_code', 'c.slug', 'p.name as parent_name', 'c.status', 'c.created_at');

        if ($filter_trashed == 1) {
            $query->whereNotNull('c.deleted_at');
        } else {
            $query->whereNull('c.deleted_at');
        }

        return datatables()->of($query)
            ->filter(function ($q) use ($request) {
                $kw = trim((string) ($request->input('search.value') ?? $request->get('customsearch', '')));
                if ($kw !== '') {
                    $q->where(function ($sub) use ($kw) {
                        $sub->where('c.name', 'like', "%{$kw}%")
                            ->orWhere('c.slug', 'like', "%{$kw}%")
                            ->orWhere('c.language_code', 'like', "%{$kw}%")
                            ->orWhere('p.name', 'like', "%{$kw}%");
                    });
                }
            })
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('language_code', fn ($row) => '<span class="label label-primary">' . strtoupper(e($row->language_code ?? 'en')) . '</span>')
            ->editColumn('parent_name', fn ($row) => e($row->parent_name ?: '—'))
            ->editColumn('status', fn ($row) => (int) $row->status === 1
                ? '<span class="label label-success">Published</span>'
                : '<span class="label label-default">Unpublished</span>')
            ->editColumn('created_at', fn ($row) => $row->created_at ? date('M-j-Y', strtotime($row->created_at)) : '')
            ->rawColumns(['sl', 'language_code', 'status'])
            ->make(true);
    }
}

