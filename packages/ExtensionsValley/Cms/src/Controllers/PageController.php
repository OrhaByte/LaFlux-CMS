<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Helpers\ThemeHelper;
use ExtensionsValley\Cms\Models\ContentVersion;
use ExtensionsValley\Cms\Models\Page;
use ExtensionsValley\Cms\Validators\PageValidation;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addPage()
    {
        $title = 'Add New Page';

        return view('Cms::admin.page.form', compact('title'));
    }

    public function savePage(Request $request)
    {
        $validation = Validator::make($request->all(), (new PageValidation)->getRules());
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addpage', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_pages',
            $request->filled('slug') ? $request->input('slug') : $request->input('title'),
            null,
            $lang
        );

        $featuredImage = $request->input('selected_media_path') ?: null;
        if ($request->hasFile('featured_image')) {
            $featuredImage = CmsHelper::uploadFeaturedImage($request->file('featured_image'), 'pages');
        }

        $template = $request->input('template', 'default') ?: 'default';
        $templateData = $this->extractTemplateData($request, $template);

        $page = Page::create([
            'language_code' => $lang,
            'title' => $request->input('title'),
            'slug' => $slug,
            'template' => $template,
            'template_data' => $templateData,
            'content' => $request->input('content'),
            'featured_image' => $featuredImage,
            'meta_title' => $request->input('meta_title'),
            'meta_keywords' => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
            'ordering' => (int) $request->input('ordering', 0),
            'status' => $request->input('status', 1),
            'published_at' => $request->filled('published_at') ? $request->input('published_at') : now(),
            'author_id' => Auth::guard('admin')->id(),
        ]);

        if ((int) $page->status === 1) {
            ContentVersion::recordSnapshot('page', $page);
        }

        return redirect('admin/ExtensionsValley/cms/list/cms_pages')
            ->with(['message' => 'Page added successfully!']);
    }

    public function editPage($id)
    {
        $title = 'Edit Page';
        $page = Page::with(['versions.author'])->findOrFail($id);

        return view('Cms::admin.page.form', compact('title', 'page'));
    }

    public function viewPage($id)
    {
        $title = 'View Page';
        $page = Page::with(['versions.author'])->findOrFail($id);
        $viewmode = 'view';

        return view('Cms::admin.page.form', compact('title', 'page', 'viewmode'));
    }

    public function updatePage(Request $request)
    {
        $page = Page::findOrFail($request->input('page_id'));
        $validation = Validator::make($request->all(), (new PageValidation)->getUpdateRules($page));
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editpage', [
                'id' => $page->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: $page->language_code ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_pages',
            $request->filled('slug') ? $request->input('slug') : $request->input('title'),
            $page->id,
            $lang
        );

        $featuredImage = $page->featured_image;
        if ($request->boolean('remove_featured_image')) {
            $featuredImage = null;
        }
        if ($request->filled('selected_media_path')) {
            $featuredImage = $request->input('selected_media_path');
        }
        if ($request->hasFile('featured_image')) {
            $featuredImage = CmsHelper::uploadFeaturedImage($request->file('featured_image'), 'pages');
        }

        $template = $request->input('template', $page->template ?: 'default') ?: 'default';
        $templateData = $this->extractTemplateData($request, $template);

        $page->update([
            'language_code' => $lang,
            'title' => $request->input('title'),
            'slug' => $slug,
            'template' => $template,
            'template_data' => $templateData,
            'content' => $request->input('content'),
            'featured_image' => $featuredImage,
            'meta_title' => $request->input('meta_title'),
            'meta_keywords' => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
            'ordering' => (int) $request->input('ordering', 0),
            'status' => $request->input('status', 1),
            'published_at' => $request->filled('published_at') ? $request->input('published_at') : $page->published_at,
        ]);

        if ((int) $page->status === 1) {
            ContentVersion::recordSnapshot('page', $page);
        }

        return redirect('admin/ExtensionsValley/cms/list/cms_pages')
            ->with(['message' => 'Page updated successfully!']);
    }

    protected function extractTemplateData(Request $request, string $template): array
    {
        $templates = ThemeHelper::getPageTemplates();
        $fields = $templates[$template]['fields'] ?? [];
        if (empty($fields)) {
            return [];
        }

        $allInput = $request->input('template_data', []);
        $raw = is_array($allInput[$template] ?? null) ? $allInput[$template] : [];
        $clean = [];

        foreach ($fields as $fKey => $fDef) {
            $type = $fDef['type'] ?? 'text';
            if ($type === 'toggle') {
                $clean[$fKey] = !empty($raw[$fKey]);
            } elseif ($type === 'number') {
                $clean[$fKey] = max(1, (int) ($raw[$fKey] ?? ($fDef['default'] ?? 6)));
            } else {
                $clean[$fKey] = isset($raw[$fKey]) ? trim((string) $raw[$fKey]) : '';
            }
        }

        return $clean;
    }
}

