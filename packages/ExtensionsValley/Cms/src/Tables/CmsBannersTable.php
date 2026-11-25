<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsBannersTable extends BaseTable
{
    public $page_title = 'Manage Banners & Sliders';
    public $table_name = 'cms_banners';
    public $acl_key = 'extensionsvalley.cms.banners';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\Banner';

    public $listable = [
        'name' => 'Banner Name',
        'slug' => 'Shortcode',
        'slides_count' => 'Slides',
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
        'add_route' => 'cms/addbanner',
        'edit_route' => 'cms/editbanner',
        'view_route' => 'cms/viewbanner',
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

        $query = DB::table('cms_banners as b')
            ->leftJoin('cms_banner_slides as s', 'b.id', '=', 's.banner_id')
            ->select('b.id', 'b.name', 'b.slug', 'b.status', 'b.created_at', DB::raw('COUNT(s.id) as slides_count'))
            ->groupBy('b.id', 'b.name', 'b.slug', 'b.status', 'b.created_at');

        if ($filter_trashed == 1) {
            $query->whereNotNull('b.deleted_at');
        } else {
            $query->whereNull('b.deleted_at');
        }

        return datatables()->of($query)
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('slug', fn ($row) => '<code>[banner slug="' . e($row->slug) . '"]</code>')
            ->editColumn('slides_count', fn ($row) => '<span class="badge">' . (int) $row->slides_count . '</span>')
            ->editColumn('status', fn ($row) => (int) $row->status === 1
                ? '<span class="label label-success">Published</span>'
                : '<span class="label label-default">Unpublished</span>')
            ->editColumn('created_at', fn ($row) => $row->created_at ? date('M-j-Y', strtotime($row->created_at)) : '')
            ->rawColumns(['sl', 'slug', 'slides_count', 'status'])
            ->make(true);
    }
}

