<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Helpers\ThemeHelper;
use ExtensionsValley\Cms\Models\Banner;
use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Models\CmsForm;
use ExtensionsValley\Cms\Models\Page;
use ExtensionsValley\Cms\Models\Tag;
use ExtensionsValley\Cms\Models\ThemeSetting;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ThemeCustomizerController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function index(Request $request)
    {
        if (!CmsHelper::hasAcl('extensionsvalley.cms.customizer', 'view')) {
            return redirect()->route('extensionsvalley.admin.dashboard')->with(['error' => 'Access Permission Denied!']);
        }

        $title = 'Theme Customizer';
        $themes = ThemeHelper::availableThemes();
        $activeTheme = ThemeHelper::activeTheme();
        $selectedTheme = $request->get('theme', $activeTheme);
        if (!isset($themes[$selectedTheme])) {
            $selectedTheme = $activeTheme;
        }

        $languages = CmsHelper::activeLanguages();
        $selectedLang = strtolower((string) $request->get('lang', CmsHelper::defaultLanguageCode()));
        if (!$languages->contains('code', $selectedLang)) {
            $selectedLang = CmsHelper::defaultLanguageCode();
        }

        $schema = ThemeHelper::getSchema($selectedTheme);
        $sectionValues = [];
        foreach (($schema['sections'] ?? []) as $sectionKey => $sectionDef) {
            $sectionValues[$sectionKey] = ThemeHelper::getSection($sectionKey, $selectedLang, $selectedTheme);
        }

        $categories = Category::where('status', 1)
            ->where('language_code', $selectedLang)
            ->orderBy('name')
            ->get(['id', 'name']);

        $tags = Tag::where('status', 1)
            ->where('language_code', $selectedLang)
            ->orderBy('name')
            ->get(['id', 'name']);

        $pages = Page::where('status', 1)
            ->where('language_code', $selectedLang)
            ->orderBy('title')
            ->get(['slug', 'title']);

        $banners = Banner::where('status', 1)->orderBy('name')->get(['slug', 'name']);
        $forms = CmsForm::where('status', 1)->orderBy('name')->get(['slug', 'name']);

        $sortOptions = [
            'latest' => 'Latest Published First',
            'oldest' => 'Oldest Published First',
            'title_asc' => 'Title (A → Z)',
            'title_desc' => 'Title (Z → A)',
            'random' => 'Random Order',
        ];

        return view('Cms::admin.customizer.index', compact(
            'title',
            'themes',
            'activeTheme',
            'selectedTheme',
            'languages',
            'selectedLang',
            'schema',
            'sectionValues',
            'categories',
            'tags',
            'pages',
            'banners',
            'forms',
            'sortOptions'
        ));
    }

    public function save(Request $request)
    {
        if (!CmsHelper::hasAcl('extensionsvalley.cms.customizer', 'edit')) {
            return redirect()->route('extensionsvalley.admin.dashboard')->with(['error' => 'Access Permission Denied!']);
        }

        $themeSlug = (string) $request->input('theme_slug', ThemeHelper::activeTheme());
        $lang = strtolower((string) $request->input('language_code', CmsHelper::defaultLanguageCode()));
        $schema = ThemeHelper::getSchema($themeSlug);
        $sectionsInput = $request->input('sections', []);

        if ($request->boolean('activate_theme') && Schema::hasTable('gen_settings')) {
            DB::table('gen_settings')->updateOrInsert(
                ['settings_key' => 'active_theme'],
                ['settings_value' => $themeSlug, 'updated_at' => now()]
            );
        }

        foreach (($schema['sections'] ?? []) as $sectionKey => $sectionDef) {
            $raw = $sectionsInput[$sectionKey] ?? [];
            $clean = [];

            foreach (($sectionDef['fields'] ?? []) as $fieldKey => $fieldDef) {
                $type = $fieldDef['type'] ?? 'text';
                if ($type === 'toggle') {
                    $clean[$fieldKey] = !empty($raw[$fieldKey]);
                } elseif ($type === 'number') {
                    $clean[$fieldKey] = max(1, (int) ($raw[$fieldKey] ?? ($fieldDef['default'] ?? 3)));
                } else {
                    $clean[$fieldKey] = isset($raw[$fieldKey]) ? trim((string) $raw[$fieldKey]) : '';
                }
            }

            ThemeSetting::updateOrCreate(
                [
                    'theme_slug' => $themeSlug,
                    'language_code' => $lang,
                    'section_key' => $sectionKey,
                ],
                ['settings' => $clean]
            );
        }

        return redirect()->route('extensionsvalley.cms.customizer', [
            'theme' => $themeSlug,
            'lang' => $lang,
        ])->with(['message' => 'Theme customizer settings saved for ' . strtoupper($lang) . '!']);
    }

    public function reset(Request $request)
    {
        if (!CmsHelper::hasAcl('extensionsvalley.cms.customizer', 'edit')) {
            return redirect()->route('extensionsvalley.admin.dashboard')->with(['error' => 'Access Permission Denied!']);
        }

        $themeSlug = (string) $request->input('theme_slug', ThemeHelper::activeTheme());
        $lang = strtolower((string) $request->input('language_code', CmsHelper::defaultLanguageCode()));

        ThemeSetting::where('theme_slug', $themeSlug)
            ->where('language_code', $lang)
            ->delete();

        return redirect()->route('extensionsvalley.cms.customizer', [
            'theme' => $themeSlug,
            'lang' => $lang,
        ])->with(['message' => 'Customizer settings reset to theme defaults for ' . strtoupper($lang) . '.']);
    }
}

