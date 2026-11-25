<?php

namespace ExtensionsValley\Cms\Tables;

use ExtensionsValley\Dashboard\Tables\BaseTable;
use Illuminate\Support\Facades\DB;

class CmsFormsTable extends BaseTable
{
    public $page_title = 'Manage Forms';
    public $table_name = 'cms_forms';
    public $acl_key = 'extensionsvalley.cms.forms';
    public $namespace = 'ExtensionsValley\Cms';
    public $overrideview = '';
    public $model_name = 'ExtensionsValley\Cms\Models\CmsForm';

    public $listable = [
        'name' => 'Form Name',
        'slug' => 'Shortcode',
        'fields_count' => 'Fields',
        'submissions_count' => 'Submissions',
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
        'add_route' => 'cms/addform',
        'edit_route' => 'cms/editform',
        'view_route' => 'cms/viewform',
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

        $query = DB::table('cms_forms as f')
            ->select(
                'f.id',
                'f.name',
                'f.slug',
                'f.status',
                DB::raw('(SELECT COUNT(*) FROM cms_form_fields WHERE form_id = f.id) as fields_count'),
                DB::raw('(SELECT COUNT(*) FROM cms_form_submissions WHERE form_id = f.id) as submissions_count')
            );

        if ($filter_trashed == 1) {
            $query->whereNotNull('f.deleted_at');
        } else {
            $query->whereNull('f.deleted_at');
        }

        return datatables()->of($query)
            ->addColumn('sl', fn ($row) => '<input type="checkbox" name="cid[]" value="' . (int) $row->id . '" class="cid_checkbox flat"/>')
            ->editColumn('slug', fn ($row) => '<code>[form slug="' . e($row->slug) . '"]</code>')
            ->editColumn('fields_count', fn ($row) => '<span class="badge">' . (int) $row->fields_count . '</span>')
            ->editColumn('submissions_count', fn ($row) => '<a href="' . route('extensionsvalley.cms.formsubmissions', ['id' => $row->id]) . '" class="btn btn-xs btn-info"><i class="fa fa-inbox"></i> ' . (int) $row->submissions_count . ' Entries</a>')
            ->editColumn('status', fn ($row) => (int) $row->status === 1
                ? '<span class="label label-success">Published</span>'
                : '<span class="label label-default">Unpublished</span>')
            ->rawColumns(['sl', 'slug', 'fields_count', 'submissions_count', 'status'])
            ->make(true);
    }
}

