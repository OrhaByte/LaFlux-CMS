<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\RedirectRule;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class RedirectController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addRedirect()
    {
        $title = 'Add URL Redirect';

        return view('Cms::admin.redirect.form', compact('title'));
    }

    public function saveRedirect(Request $request)
    {
        $source = RedirectRule::normalizeSourcePath((string) $request->input('source_url', ''));
        $request->merge(['source_url' => $source]);

        $validation = Validator::make($request->all(), [
            'source_url' => 'required|max:500|unique:cms_redirects,source_url',
            'target_url' => 'required|max:500',
            'status_code' => 'required|in:301,302,307,308',
            'status' => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addredirect', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        RedirectRule::create([
            'source_url' => $source,
            'target_url' => CmsHelper::sanitizeUrl($request->input('target_url')),
            'status_code' => (int) $request->input('status_code', 301),
            'status' => (int) $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_redirects')
            ->with(['message' => 'Redirect rule created successfully!']);
    }

    public function editRedirect($id)
    {
        $title = 'Edit URL Redirect';
        $redirect = RedirectRule::findOrFail($id);

        return view('Cms::admin.redirect.form', compact('title', 'redirect'));
    }

    public function viewRedirect($id)
    {
        $title = 'View URL Redirect';
        $redirect = RedirectRule::findOrFail($id);
        $viewmode = 'view';

        return view('Cms::admin.redirect.form', compact('title', 'redirect', 'viewmode'));
    }

    public function updateRedirect(Request $request)
    {
        $redirect = RedirectRule::findOrFail($request->input('redirect_id'));
        $source = RedirectRule::normalizeSourcePath((string) $request->input('source_url', ''));
        $request->merge(['source_url' => $source]);

        $validation = Validator::make($request->all(), [
            'source_url' => 'required|max:500|unique:cms_redirects,source_url,' . $redirect->id,
            'target_url' => 'required|max:500',
            'status_code' => 'required|in:301,302,307,308',
            'status' => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editredirect', [
                'id' => $redirect->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $redirect->update([
            'source_url' => $source,
            'target_url' => CmsHelper::sanitizeUrl($request->input('target_url')),
            'status_code' => (int) $request->input('status_code', 301),
            'status' => (int) $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_redirects')
            ->with(['message' => 'Redirect rule updated successfully!']);
    }
}

