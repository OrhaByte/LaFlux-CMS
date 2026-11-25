<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Models\Language;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addLanguage()
    {
        $title = 'Add New Language';

        return view('Cms::admin.language.form', compact('title'));
    }

    public function saveLanguage(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'code' => 'required|alpha|size:2|unique:cms_languages,code',
            'direction' => 'required|in:ltr,rtl',
            'status' => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addlanguage', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $isDefault = $request->boolean('is_default');
        if ($isDefault) {
            Language::where('is_default', 1)->update(['is_default' => 0]);
        }

        Language::create([
            'name' => $request->input('name'),
            'code' => strtolower($request->input('code')),
            'direction' => $request->input('direction', 'ltr'),
            'is_default' => $isDefault,
            'status' => $isDefault ? 1 : (int) $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_languages')
            ->with(['message' => 'Language added successfully!']);
    }

    public function editLanguage($id)
    {
        $title = 'Edit Language';
        $language = Language::findOrFail($id);

        return view('Cms::admin.language.form', compact('title', 'language'));
    }

    public function viewLanguage($id)
    {
        $title = 'View Language';
        $language = Language::findOrFail($id);
        $viewmode = 'view';

        return view('Cms::admin.language.form', compact('title', 'language', 'viewmode'));
    }

    public function updateLanguage(Request $request)
    {
        $language = Language::findOrFail($request->input('language_id'));
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'code' => 'required|alpha|size:2|unique:cms_languages,code,' . $language->id,
            'direction' => 'required|in:ltr,rtl',
            'status' => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editlanguage', [
                'id' => $language->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $isDefault = $request->boolean('is_default') || $language->is_default;
        if ($request->boolean('is_default')) {
            Language::where('id', '<>', $language->id)->update(['is_default' => 0]);
        }

        $language->update([
            'name' => $request->input('name'),
            'code' => strtolower($request->input('code')),
            'direction' => $request->input('direction', 'ltr'),
            'is_default' => $isDefault,
            'status' => $isDefault ? 1 : (int) $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_languages')
            ->with(['message' => 'Language updated successfully!']);
    }
}

