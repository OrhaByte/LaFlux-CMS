<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsLanguagesTable extends BaseTable
{
    public $page_title = 'Manage Languages';
    public $table_name = 'cms_languages';
    public $acl_key = 'extensionsvalley.cms.languages';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\Language';

    public $listable = [
        'name' => 'Language Name',
        'code' => 'Code',
        'direction' => 'Direction',
        'is_default' => 'Default',
        'status' => 'Status',
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
        'add_route' => 'cms/addlanguage',
        'edit_route' => 'cms/editlanguage',
        'view_route' => 'cms/viewlanguage',
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

        $query = DB::table('cms_languages')
            ->select('id', 'name', 'code', 'direction', 'is_default', 'status');

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
                        $sub->where('name', 'like', "%{$kw}%")
                            ->orWhere('code', 'like', "%{$kw}%")
                            ->orWhere('direction', 'like', "%{$kw}%");
                    });
                }
            })
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('code', fn ($row) => '<code>' . e(strtolower($row->code)) . '</code>')
            ->editColumn('direction', fn ($row) => strtoupper(e($row->direction)))
            ->editColumn('is_default', fn ($row) => $row->is_default ? '<span class="label label-primary">Default</span>' : '—')
            ->editColumn('status', fn ($row) => (int) $row->status === 1
                ? '<span class="label label-success">Active</span>'
                : '<span class="label label-default">Inactive</span>')
            ->rawColumns(['sl', 'code', 'is_default', 'status'])
            ->make(true);
    }
}

