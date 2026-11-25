<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Validators\CategoryValidation;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addCategory()
    {
        $title = 'Add New Category';
        $parents = Category::getCategories();

        return view('Cms::admin.category.form', compact('title', 'parents'));
    }

    public function saveCategory(Request $request)
    {
        $validation = Validator::make($request->all(), (new CategoryValidation)->getRules());
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addcategory', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_categories',
            $request->filled('slug') ? $request->input('slug') : $request->input('name'),
            null,
            $lang
        );

        Category::create([
            'language_code' => $lang,
            'name' => $request->input('name'),
            'slug' => $slug,
            'parent_id' => $request->input('parent_id') ?: null,
            'description' => $request->input('description'),
            'meta_title' => $request->input('meta_title'),
            'meta_keywords' => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
            'status' => $request->input('status', 1),
            'created_by' => Auth::guard('admin')->id(),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_categories')
            ->with(['message' => 'Category added successfully!']);
    }

    public function editCategory($id)
    {
        $title = 'Edit Category';
        $category = Category::findOrFail($id);
        $parents = Category::getCategories($category->id);

        return view('Cms::admin.category.form', compact('title', 'category', 'parents'));
    }

    public function viewCategory($id)
    {
        $title = 'View Category';
        $category = Category::findOrFail($id);
        $parents = Category::getCategories($category->id);
        $viewmode = 'view';

        return view('Cms::admin.category.form', compact('title', 'category', 'parents', 'viewmode'));
    }

    public function updateCategory(Request $request)
    {
        $category = Category::findOrFail($request->input('category_id'));
        $validation = Validator::make($request->all(), (new CategoryValidation)->getUpdateRules($category));
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editcategory', [
                'id' => $category->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: $category->language_code ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_categories',
            $request->filled('slug') ? $request->input('slug') : $request->input('name'),
            $category->id,
            $lang
        );

        $category->update([
            'language_code' => $lang,
            'name' => $request->input('name'),
            'slug' => $slug,
            'parent_id' => $request->input('parent_id') ?: null,
            'description' => $request->input('description'),
            'meta_title' => $request->input('meta_title'),
            'meta_keywords' => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
            'status' => $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_categories')
            ->with(['message' => 'Category updated successfully!']);
    }
}

