<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\Tag;
use ExtensionsValley\Cms\Validators\TagValidation;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addTag()
    {
        $title = 'Add New Tag';

        return view('Cms::admin.tag.form', compact('title'));
    }

    public function saveTag(Request $request)
    {
        $validation = Validator::make($request->all(), (new TagValidation)->getRules());
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addtag', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_tags',
            $request->filled('slug') ? $request->input('slug') : $request->input('name'),
            null,
            $lang
        );

        Tag::create([
            'language_code' => $lang,
            'name' => $request->input('name'),
            'slug' => $slug,
            'status' => $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_tags')
            ->with(['message' => 'Tag added successfully!']);
    }

    public function editTag($id)
    {
        $title = 'Edit Tag';
        $tag = Tag::findOrFail($id);

        return view('Cms::admin.tag.form', compact('title', 'tag'));
    }

    public function viewTag($id)
    {
        $title = 'View Tag';
        $tag = Tag::findOrFail($id);
        $viewmode = 'view';

        return view('Cms::admin.tag.form', compact('title', 'tag', 'viewmode'));
    }

    public function updateTag(Request $request)
    {
        $tag = Tag::findOrFail($request->input('tag_id'));
        $validation = Validator::make($request->all(), (new TagValidation)->getUpdateRules($tag));
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.edittag', [
                'id' => $tag->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: $tag->language_code ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_tags',
            $request->filled('slug') ? $request->input('slug') : $request->input('name'),
            $tag->id,
            $lang
        );

        $tag->update([
            'language_code' => $lang,
            'name' => $request->input('name'),
            'slug' => $slug,
            'status' => $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_tags')
            ->with(['message' => 'Tag updated successfully!']);
    }
}

