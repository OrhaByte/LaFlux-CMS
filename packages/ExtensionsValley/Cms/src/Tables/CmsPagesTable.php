<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsPagesTable extends BaseTable
{
    public $page_title = 'Manage Pages';
    public $table_name = 'cms_pages';
    public $acl_key = 'extensionsvalley.cms.pages';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\Page';

    public $listable = [
        'title' => 'Title',
        'language_code' => 'Lang',
        'slug' => 'Slug',
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
        'add_route' => 'cms/addpage',
        'edit_route' => 'cms/editpage',
        'view_route' => 'cms/viewpage',
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

        $query = DB::table('cms_pages')
            ->select('id', 'title', 'language_code', 'slug', 'published_at', 'status', 'created_at');

        if ($filter_trashed == 1) {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        return datatables()->of($query)
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('language_code', fn ($row) => '<span class="label label-primary">' . strtoupper(e($row->language_code ?? 'en')) . '</span>')
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

