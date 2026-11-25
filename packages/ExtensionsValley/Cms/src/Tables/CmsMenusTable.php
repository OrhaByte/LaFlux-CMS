<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsMenusTable extends BaseTable
{
    public $page_title = 'Manage Menus';
    public $table_name = 'cms_menus';
    public $acl_key = 'extensionsvalley.cms.menus';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\Menu';

    public $listable = [
        'name' => 'Menu Name',
        'language_code' => 'Lang',
        'slug' => 'Slug',
        'location' => 'Location',
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
        'add_route' => 'cms/addmenu',
        'edit_route' => 'cms/editmenu',
        'view_route' => 'cms/viewmenu',
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

        $query = DB::table('cms_menus')
            ->select('id', 'name', 'language_code', 'slug', 'location', 'status', 'created_at');

        if ($filter_trashed == 1) {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        return datatables()->of($query)
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('language_code', fn ($row) => '<span class="label label-primary">' . strtoupper(e($row->language_code ?? 'en')) . '</span>')
            ->editColumn('location', fn ($row) => '<span class="label label-info">' . e($row->location) . '</span>')
            ->editColumn('status', fn ($row) => (int) $row->status === 1
                ? '<span class="label label-success">Published</span>'
                : '<span class="label label-default">Unpublished</span>')
            ->editColumn('created_at', fn ($row) => $row->created_at ? date('M-j-Y', strtotime($row->created_at)) : '')
            ->rawColumns(['sl', 'language_code', 'location', 'status'])
            ->make(true);
    }
}

