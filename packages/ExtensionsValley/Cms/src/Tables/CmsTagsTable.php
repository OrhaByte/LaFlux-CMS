<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsTagsTable extends BaseTable
{
    public $page_title = 'Manage Tags';
    public $table_name = 'cms_tags';
    public $acl_key = 'extensionsvalley.cms.tags';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\Tag';

    public $listable = [
        'name' => 'Tag Name',
        'language_code' => 'Lang',
        'slug' => 'Slug',
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
        'add_route' => 'cms/addtag',
        'edit_route' => 'cms/edittag',
        'view_route' => 'cms/viewtag',
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

        $query = DB::table('cms_tags')
            ->select('id', 'name', 'language_code', 'slug', 'status', 'created_at');

        if ($filter_trashed == 1) {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        return datatables()->of($query)
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('language_code', fn ($row) => '<span class="label label-primary">' . strtoupper(e($row->language_code ?? 'en')) . '</span>')
            ->editColumn('status', fn ($row) => (int) $row->status === 1
                ? '<span class="label label-success">Published</span>'
                : '<span class="label label-default">Unpublished</span>')
            ->editColumn('created_at', fn ($row) => $row->created_at ? date('M-j-Y', strtotime($row->created_at)) : '')
            ->rawColumns(['sl', 'language_code', 'status'])
            ->make(true);
    }
}

