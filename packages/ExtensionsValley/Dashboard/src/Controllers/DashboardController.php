<?php
namespace ExtensionsValley\Dashboard\Controllers;

use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Routing\Controller;
use ExtensionsValley\Dashboard\Models\WebSettings;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

    use DashboardTraits;

    public function __construct()
    {

        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function getDashboard()
    {

        $title = 'Dashboard';
        return \View::make('Dashboard::dashboard.index', compact('title'));
    }

    public function getBasicView($vendor,$namespace, $table_name)
    {

        $namespace = $vendor."\\" . str_replace(" ", "", ucwords(str_replace('-', " ", $namespace)));
        $table_class = $namespace . '\Tables\\' . str_replace("_", "",ucwords( $table_name, "_")) . "Table";

        if (!class_exists($table_class) || !is_subclass_of($table_class, \ExtensionsValley\Dashboard\Tables\BaseTable::class)) {
            abort(404);
        }

        $table = with(new $table_class);
        $table->vendor = $vendor;
        $table->namespace = $namespace;
        if (isset($table->overrideview)) {

            if (trim($table->overrideview) != "") {

                $partials_path = $table->overrideview;

            } else {

                $partials_path = "Dashboard::dashboard.partials.table";
            }


            return \View::make($partials_path, ['title' => $table->page_title
                , 'table' => $table,
            ]);
        }

    }

    public function getAjaxView(Request $request, $namespace, $table_name)
    {

        $namespace =  str_replace(" ", "", ucwords(str_replace('-', " ", $namespace)));

        $table_class = $namespace . '\Tables\\' . str_replace("_", "",ucwords( $table_name, "_")) . "Table";

        if (!class_exists($table_class) || !is_subclass_of($table_class, \ExtensionsValley\Dashboard\Tables\BaseTable::class)) {
            abort(404);
        }

        $table = with(new $table_class);

        return $table->getQuery($request);
    }

    public function getCommonAction(Request $request)
    {

        $cid = (array) $request->input('cid', []);
        $action_type = $request->input('action_type');
        $table_name = $request->input('table_name');
        $acl_key = base64_decode((string) $request->input('acl_key'));
        $model_name = ltrim(base64_decode((string) $request->input('model_name')), '\\');
        $raw_return_url = base64_decode((string) $request->input('return_url'));

        // Prevent open redirect: allow only same-origin or relative paths
        $appBase = rtrim(url('/'), '/');
        if (str_starts_with($raw_return_url, $appBase) || (str_starts_with($raw_return_url, '/') && !str_starts_with($raw_return_url, '//'))) {
            $return_url = $raw_return_url;
        } else {
            $return_url = url('/admin/dashboard');
        }

        // Strict model whitelist: must be a Dashboard or Cms Eloquent Model matching $table_name
        $isAllowedNamespace = str_starts_with($model_name, 'ExtensionsValley\\Dashboard\\Models\\')
            || str_starts_with($model_name, 'ExtensionsValley\\Cms\\Models\\');
        if (
            !$isAllowedNamespace
            || !class_exists($model_name)
            || !is_subclass_of($model_name, \Illuminate\Database\Eloquent\Model::class)
            || (new $model_name)->getTable() !== $table_name
        ) {
            return redirect($return_url)->with(['error' => 'Unauthorized model action detected!']);
        }

        $mesaage = "Please select an actions to perform";

        if (\Schema::hasTable($table_name) && sizeof($cid) > 0) {

            $acl_status = 1;
            if ($action_type == "enable" || $action_type == "disable") {
                $acl_status = $this->checkACLPermission($acl_key, 'edit');
            } else {
                $acl_status = $this->checkACLPermission($acl_key, 'trash');
            }

            if ($acl_status == 0) {
                return redirect($return_url)->with(['error' => 'Access Permission Denied!']);
            }

            if ($action_type == "enable") {

                $model_name::whereIn('id', $cid)->update(array('status' => 1));
                $mesaage = "Records Published successfully";

            } elseif ($action_type == "disable") {

                $relation_status = $model_name::getRlationstatus($cid);

                if ($relation_status == 0) {

                    $model_name::whereIn('id', $cid)->update(array('status' => 0));
                    $mesaage = "Records Unpublished successfully";

                } else {
                    return redirect($return_url)->with(['error' => 'Sorry unable to update ! Some related records found.']);
                }

            } elseif ($action_type == "remove") {

                $relation_status = $model_name::getRlationstatus($cid);

                if ($relation_status == 0) {

                    $model_name::whereIn('id', $cid)->delete();
                    $mesaage = "Records Trashed successfully";

                } else {
                    return redirect($return_url)->with(['error' => 'Sorry unable to trash ! Some related records found, may be in Trashed list.']);
                }
            } elseif ($action_type == "restore") {

                    $model_name::whereIn('id', $cid)->restore();
                    $mesaage = "Records Restored successfully";


            } elseif ($action_type == "forcedelete") {

                    $model_name::whereIn('id', $cid)->forceDelete();
                    $mesaage = "Records Deleted successfully";


            } elseif ($action_type == "view") {

                $redirect_path = $model_name::$view_route;

                return redirect()->route($redirect_path, ['id' => $cid[0], 'return_url' => base64_encode($return_url)]);
            }

            if (method_exists($model_name, 'getCommonActionHandler')) {
                //Check Action function in modles.
                call_user_func_array([$model_name, 'getCommonActionHandler'], [$action_type, $cid]);
            }
            return redirect($return_url)->with(['message' => $mesaage]);

        } else {

            return redirect($return_url)->with(['error' => 'Unauthorized action detected !']);
        }

    }

    public function checkACLPermission($acl_key, $action)
    {

        $groups = \Auth::guard('admin')->user()->groups;

        return \DB::table('acl_permission')
            ->Where('group_id', $groups)
            ->where('acl_key', $acl_key)
            ->Where($action, 1)
            ->count();

    }

    public function getSettings()
    {


        $title = 'General Settings';
        if (\Auth::guard('admin')->user()->groups != 1) {
            return redirect('admin/dashboard')->with(['error' => 'Unauthorized action detected']);
        }

        return \View::make('Dashboard::dashboard.settings', compact('title'));
    }

    public function updateSettings(Request $request)
    {
        if (\Auth::guard('admin')->user()->groups != 1) {
            return redirect('admin/dashboard')->with(['error' => 'Unauthorized action detected']);
        }

        ## Validate Logo & Favicon before saving
        $validation = \Validator::make($request->all()
            , ['site_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120'
                , 'fav_icon' => 'nullable|mimes:ico,png,jpg,jpeg,gif|max:1024'
            ]);
        if ($validation->fails()) {
            return redirect('admin/gensettings')->withErrors($validation)->withInput();
        }

        $settings = (array) $request->input('settings', []);

        foreach ($settings as $key => $value) {
            $value = (string) ($value ?? '');
            if (\DB::table('gen_settings')->Where('settings_key', $key)->count()){
                \DB::table('gen_settings')->Where('settings_key', $key)
                ->update(['settings_value' => $value]);
            }else{
                WebSettings::Create(['settings_key' => $key, 'settings_value' => $value]);

            }
             ## Reset or update cache entries
            \Cache::forget($key);
            \Cache::forever($key, $value);

        }


        $media = "";
        if ($request->file('site_logo')) {
            $destinationPath = "packages/extensionsvalley/dashboard/images/";
            $final_location = $destinationPath;
            $file_name = "logo";
            $request->file('site_logo')->move($final_location, $file_name . '.' . $request->file('site_logo')->getClientOriginalExtension());
            $media = $final_location . $file_name . '.' . $request->file('site_logo')->getClientOriginalExtension()."?i=".rand(1,10);
        } else {
            $media = \WebConf::get('site_logo');
        }
        $favicon = "";
        if ($request->file('fav_icon')) {
            $destinationPath = "packages/extensionsvalley/dashboard/images/";
            $final_location = $destinationPath;
            $file_name = "favicon";
            $request->file('fav_icon')->move($final_location, $file_name . '.' . $request->file('fav_icon')->getClientOriginalExtension());
            $favicon = $final_location . $file_name . '.' . $request->file('fav_icon')->getClientOriginalExtension()."?i=".rand(1,10);
        } else {
            $favicon = \WebConf::get('fav_icon');
        }

        if ($media != "") {
            if (\WebConf::checkKey('site_logo')) {
                \DB::table('gen_settings')->Where('settings_key', 'site_logo')
                    ->update(['settings_value' => $media]);
            } else {
                WebSettings::Create([
                    'settings_key' => 'site_logo'
                    , 'settings_value' => $media
                ]);
            }
             ## Reset or update cache entries
            \Cache::forget('site_logo');
            \Cache::forever('site_logo', $media);
        }
        if ($favicon != "") {

            if (\WebConf::checkKey('fav_icon')) {
                \DB::table('gen_settings')->Where('settings_key', 'fav_icon')
                    ->update(['settings_value' => $favicon]);
            } else {
                WebSettings::Create([
                    'settings_key' => 'fav_icon'
                    , 'settings_value' => $favicon
                ]);
            }
             ## Reset or update cache entries
            \Cache::forget('fav_icon');
            \Cache::forever('fav_icon', $favicon);
        }

        return redirect('admin/gensettings')->with(['message' => 'Settings update successfully!']);
    }

    public function replaceUrls(Request $request)
    {
        if (\Auth::guard('admin')->user()->groups != 1) {
            return redirect('admin/dashboard')->with(['error' => 'Unauthorized action detected']);
        }

        $validation = \Validator::make($request->all(), [
            'find_url' => 'required|string|max:500',
            'replace_url' => 'required|string|max:500',
            'targets' => 'required|array|min:1',
        ]);

        if ($validation->fails()) {
            return redirect('admin/gensettings')->withErrors($validation)->withInput();
        }

        $find = rtrim(trim($request->input('find_url')), '/');
        $replace = rtrim(trim($request->input('replace_url')), '/');
        $dryRun = $request->boolean('dry_run');
        $selected = $request->input('targets', []);

        if ($find === '' || $find === $replace) {
            return redirect('admin/gensettings')
                ->with(['warning' => 'Find URL and Replace URL must be non-empty and different.'])
                ->withInput();
        }

        $targetMap = [
            'acl_permission' => ['label' => 'Admin Menus & ACL Permissions', 'columns' => ['link']],
            'cms_menu_items' => ['label' => 'CMS Menu Items', 'columns' => ['url']],
            'cms_posts' => ['label' => 'CMS Posts (Content & Featured Image)', 'columns' => ['content', 'featured_image']],
            'cms_pages' => ['label' => 'CMS Pages (Content & Featured Image)', 'columns' => ['content', 'featured_image']],
            'cms_categories' => ['label' => 'CMS Categories (Description)', 'columns' => ['description']],
            'cms_media' => ['label' => 'CMS Media Library', 'columns' => ['file_path']],
            'gen_settings' => ['label' => 'General Settings', 'columns' => ['settings_value']],
        ];

        $report = [];
        $totalAffected = 0;

        foreach ($selected as $table) {
            if (!isset($targetMap[$table]) || !\Schema::hasTable($table)) {
                continue;
            }

            $tableCount = 0;
            foreach ($targetMap[$table]['columns'] as $col) {
                if (!\Schema::hasColumn($table, $col)) {
                    continue;
                }

                $matches = \DB::table($table)->where($col, 'like', '%' . $find . '%')->count();
                if ($matches > 0 && !$dryRun) {
                    \DB::table($table)
                        ->where($col, 'like', '%' . $find . '%')
                        ->update([$col => \DB::raw('REPLACE(`' . $col . '`, ' . \DB::getPdo()->quote($find) . ', ' . \DB::getPdo()->quote($replace) . ')')]);
                }
                $tableCount += $matches;
            }

            $report[] = [
                'table' => $table,
                'label' => $targetMap[$table]['label'],
                'count' => $tableCount,
            ];
            $totalAffected += $tableCount;
        }

        if (!$dryRun && in_array('gen_settings', $selected, true) && \Schema::hasTable('gen_settings')) {
            foreach (\DB::table('gen_settings')->get() as $setting) {
                \Cache::forget($setting->settings_key);
                \Cache::forever($setting->settings_key, $setting->settings_value);
            }
        }

        $msg = $dryRun
            ? "Dry run complete: found {$totalAffected} matching field(s). Uncheck 'Dry Run' to apply changes."
            : "URL replacement complete: updated {$totalAffected} field(s) across selected tables.";

        return redirect('admin/gensettings')
            ->with(['message' => $msg, 'url_replace_report' => $report, 'url_replace_dry_run' => $dryRun])
            ->withInput();
    }

}
