<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Models\Menu;
use ExtensionsValley\Cms\Models\MenuItem;
use ExtensionsValley\Cms\Models\Page;
use ExtensionsValley\Cms\Models\Post;
use ExtensionsValley\Cms\Validators\MenuValidation;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addMenu()
    {
        $title = 'Add New Menu';

        return view('Cms::admin.menu.form', compact('title'));
    }

    public function saveMenu(Request $request)
    {
        $validation = Validator::make($request->all(), (new MenuValidation)->getRules());
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addmenu', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_menus',
            $request->filled('slug') ? $request->input('slug') : $request->input('name'),
            null,
            $lang
        );

        $menu = Menu::create([
            'language_code' => $lang,
            'name' => $request->input('name'),
            'slug' => $slug,
            'location' => $request->input('location', 'header'),
            'status' => $request->input('status', 1),
        ]);

        return redirect()->route('extensionsvalley.cms.editmenu', [
            'id' => $menu->id,
            'accesstoken' => $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.menus'),
        ])->with(['message' => 'Menu created! Now add menu items below.']);
    }

    public function editMenu($id)
    {
        $title = 'Edit Menu & Items';
        $menu = Menu::with(['items.parent'])->findOrFail($id);
        $pages = Page::getPages();
        $posts = Post::getPosts();
        $categories = Category::getCategories();

        return view('Cms::admin.menu.form', compact('title', 'menu', 'pages', 'posts', 'categories'));
    }

    public function viewMenu($id)
    {
        $title = 'View Menu';
        $menu = Menu::with(['items.parent'])->findOrFail($id);
        $pages = Page::getPages();
        $posts = Post::getPosts();
        $categories = Category::getCategories();
        $viewmode = 'view';

        return view('Cms::admin.menu.form', compact('title', 'menu', 'pages', 'posts', 'categories', 'viewmode'));
    }

    public function updateMenu(Request $request)
    {
        $menu = Menu::findOrFail($request->input('menu_id'));
        $validation = Validator::make($request->all(), (new MenuValidation)->getUpdateRules($menu));
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editmenu', [
                'id' => $menu->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: $menu->language_code ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_menus',
            $request->filled('slug') ? $request->input('slug') : $request->input('name'),
            $menu->id,
            $lang
        );

        $menu->update([
            'language_code' => $lang,
            'name' => $request->input('name'),
            'slug' => $slug,
            'location' => $request->input('location', 'header'),
            'status' => $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_menus')
            ->with(['message' => 'Menu updated successfully!']);
    }

    public function saveMenuItem(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'menu_id' => 'required|integer|exists:cms_menus,id',
            'title' => 'required|max:200',
            'type' => 'required|in:custom,page,post,category',
            'reference_id' => 'nullable|integer',
            'url' => 'nullable|max:500',
            'parent_id' => 'nullable|integer|exists:cms_menu_items,id',
            'target' => 'required|in:_self,_blank',
            'ordering' => 'nullable|integer',
        ]);

        $menuId = $request->input('menu_id');
        $token = $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.menus');

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editmenu', [
                'id' => $menuId,
                'accesstoken' => $token,
            ])->withErrors($validation)->withInput();
        }

        $type = $request->input('type');
        $referenceId = null;
        $url = null;

        if ($type === 'custom') {
            $url = $request->input('url') ?: '#';
        } elseif ($type === 'page') {
            $referenceId = $request->input('page_id');
        } elseif ($type === 'post') {
            $referenceId = $request->input('post_id');
        } elseif ($type === 'category') {
            $referenceId = $request->input('category_id');
        }

        MenuItem::create([
            'menu_id' => $menuId,
            'parent_id' => $request->input('parent_id') ?: null,
            'title' => $request->input('title'),
            'type' => $type,
            'reference_id' => $referenceId,
            'url' => $url,
            'target' => $request->input('target', '_self'),
            'ordering' => (int) $request->input('ordering', 0),
            'status' => 1,
        ]);

        return redirect()->route('extensionsvalley.cms.editmenu', [
            'id' => $menuId,
            'accesstoken' => $token,
        ])->with(['message' => 'Menu item added!']);
    }

    public function deleteMenuItem(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);
        $menuId = $item->menu_id;
        // Re-parent child items to root so nothing gets orphaned
        MenuItem::where('parent_id', $item->id)->update(['parent_id' => null]);
        $item->delete();

        $token = $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.menus');

        return redirect()->route('extensionsvalley.cms.editmenu', [
            'id' => $menuId,
            'accesstoken' => $token,
        ])->with(['message' => 'Menu item removed!']);
    }

    public function reorderMenuItems(Request $request)
    {
        $order = $request->input('order', []);
        if (!is_array($order) || empty($order)) {
            return response()->json(['status' => 'error', 'message' => 'No items to reorder'], 422);
        }

        foreach ($order as $index => $itemId) {
            MenuItem::where('id', (int) $itemId)->update(['ordering' => $index + 1]);
        }

        return response()->json(['status' => 'ok']);
    }
}

