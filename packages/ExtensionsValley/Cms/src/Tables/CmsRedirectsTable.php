<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsRedirectsTable extends BaseTable
{
    public $page_title = 'Manage URL Redirects';
    public $table_name = 'cms_redirects';
    public $acl_key = 'extensionsvalley.cms.redirects';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\RedirectRule';

    public $listable = [
        'source_url' => 'Old URL (Source)',
        'status_code' => 'Type',
        'target_url' => 'Target URL',
        'hits' => 'Hits',
        'status' => 'Status',
    ];

    public $show_toolbar = [
        'view' => 'Show',
        'add' => 'Add',
        'edit' => 'Edit',
        'publish' => 'Enable',
        'unpublish' => 'Disable',
        'trash' => 'Trash',
        'restore' => 'Restore',
        'forcedelete' => 'Force Delete',
    ];

    public $routes = [
        'add_route' => 'cms/addredirect',
        'edit_route' => 'cms/editredirect',
        'view_route' => 'cms/viewredirect',
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

        $query = DB::table('cms_redirects')
            ->select('id', 'source_url', 'status_code', 'target_url', 'hits', 'status');

        if ($filter_trashed == 1) {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        return datatables()->of($query)
            ->filter(function ($q) use ($request) {
                $kw = trim((string) ($request->input('search.value') ?? $request->get('customsearch', '')));
                if ($kw !== '') {
                    $q->where(function ($sub) use ($kw) {
                        $sub->where('source_url', 'like', "%{$kw}%")
                            ->orWhere('target_url', 'like', "%{$kw}%")
                            ->orWhere('status_code', 'like', "%{$kw}%");
                    });
                }
            })
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('source_url', fn ($row) => '<code>' . e($row->source_url) . '</code>')
            ->editColumn('status_code', fn ($row) => '<span class="label label-info">' . (int) $row->status_code . '</span>')
            ->editColumn('target_url', fn ($row) => '<code>' . e($row->target_url) . '</code>')
            ->editColumn('hits', fn ($row) => '<span class="badge">' . number_format((int) $row->hits) . '</span>')
            ->editColumn('status', fn ($row) => (int) $row->status === 1
                ? '<span class="label label-success">Active</span>'
                : '<span class="label label-default">Disabled</span>')
            ->rawColumns(['sl', 'source_url', 'status_code', 'target_url', 'hits', 'status'])
            ->make(true);
    }
}

