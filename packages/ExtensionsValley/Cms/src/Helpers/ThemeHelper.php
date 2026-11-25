<?php

namespace ExtensionsValley\Cms\Helpers;

use ExtensionsValley\Cms\Models\Post;
use ExtensionsValley\Cms\Models\ThemeSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ThemeHelper
{
    public static function availableThemes(): array
    {
        $themesDir = resource_path('themes');
        $themes = [];

        if (!is_dir($themesDir)) {
            return $themes;
        }

        foreach (scandir($themesDir) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $jsonPath = $themesDir . DIRECTORY_SEPARATOR . $entry . DIRECTORY_SEPARATOR . 'theme.json';
            if (is_file($jsonPath)) {
                $decoded = json_decode((string) file_get_contents($jsonPath), true);
                if (is_array($decoded)) {
                    $decoded['slug'] = $decoded['slug'] ?? $entry;
                    $themes[$decoded['slug']] = $decoded;
                }
            }
        }

        return $themes;
    }

    public static function activeTheme(): string
    {
        if (Schema::hasTable('gen_settings')) {
            $stored = DB::table('gen_settings')->where('settings_key', 'active_theme')->value('settings_value');
            if ($stored && is_dir(resource_path('themes/' . $stored . '/views'))) {
                return $stored;
            }
        }

        return 'default';
    }

    public static function getSchema(?string $themeSlug = null): array
    {
        $slug = $themeSlug ?: self::activeTheme();
        $jsonPath = resource_path('themes/' . $slug . '/theme.json');

        if (!is_file($jsonPath)) {
            return ['name' => ucfirst($slug), 'slug' => $slug, 'sections' => []];
        }

        $decoded = json_decode((string) file_get_contents($jsonPath), true);

        return is_array($decoded) ? $decoded : ['name' => ucfirst($slug), 'slug' => $slug, 'sections' => []];
    }

    public static function getSection(string $sectionKey, ?string $lang = null, ?string $themeSlug = null): array
    {
        $slug = $themeSlug ?: self::activeTheme();
        $code = strtolower($lang ?: CmsHelper::currentLanguageCode());
        $schema = self::getSchema($slug);

        $defaults = [];
        $sectionDef = $schema['sections'][$sectionKey] ?? null;
        if (is_array($sectionDef) && !empty($sectionDef['fields'])) {
            foreach ($sectionDef['fields'] as $fieldKey => $fieldDef) {
                $defaults[$fieldKey] = $fieldDef['default'] ?? null;
            }
        }

        if (!Schema::hasTable('cms_theme_settings')) {
            return $defaults;
        }

        $record = ThemeSetting::where('theme_slug', $slug)
            ->where('language_code', $code)
            ->where('section_key', $sectionKey)
            ->first();

        if (!$record && $code !== CmsHelper::defaultLanguageCode()) {
            $record = ThemeSetting::where('theme_slug', $slug)
                ->where('language_code', CmsHelper::defaultLanguageCode())
                ->where('section_key', $sectionKey)
                ->first();
        }

        if (!$record || !is_array($record->settings)) {
            return $defaults;
        }

        return array_merge($defaults, $record->settings);
    }

    public static function getSectionPosts(array $config, ?string $lang = null): Collection
    {
        $code = strtolower($lang ?: CmsHelper::currentLanguageCode());
        $limit = max(1, min(30, (int) ($config['limit'] ?? 3)));
        $categoryId = !empty($config['category_id']) ? (int) $config['category_id'] : null;
        $tagId = !empty($config['tag_id']) ? (int) $config['tag_id'] : null;
        $sortOrder = (string) ($config['sort_order'] ?? 'latest');

        $query = Post::published()
            ->where('language_code', $code)
            ->with(['category', 'tags', 'author']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($tagId) {
            $query->whereHas('tags', fn ($q) => $q->where('cms_tags.id', $tagId));
        }

        switch ($sortOrder) {
            case 'oldest':
                $query->orderBy('published_at')->orderBy('id');
                break;
            case 'title_asc':
                $query->orderBy('title');
                break;
            case 'title_desc':
                $query->orderByDesc('title');
                break;
            case 'random':
                $query->inRandomOrder();
                break;
            case 'latest':
            default:
                $query->orderByDesc('published_at')->orderByDesc('id');
                break;
        }

        return $query->limit($limit)->get();
    }

    public static function getPageTemplates(?string $themeSlug = null): array
    {
        $schema = self::getSchema($themeSlug);
        $templates = $schema['page_templates'] ?? [];

        if (!isset($templates['default'])) {
            $templates = array_merge([
                'default' => [
                    'label' => 'Default Page',
                    'view' => 'Theme::page',
                    'fields' => [],
                ],
            ], $templates);
        }

        return $templates;
    }

    public static function resolvePageTemplateView(?string $templateKey, ?string $themeSlug = null): string
    {
        $key = $templateKey ?: 'default';
        $templates = self::getPageTemplates($themeSlug);
        $view = $templates[$key]['view'] ?? 'Theme::page';

        return view()->exists($view) ? $view : 'Theme::page';
    }

    public static function getPageTemplateData(\ExtensionsValley\Cms\Models\Page $page, ?string $themeSlug = null): array
    {
        $key = $page->template ?: 'default';
        $templates = self::getPageTemplates($themeSlug);
        $fields = $templates[$key]['fields'] ?? [];

        $defaults = [];
        foreach ($fields as $fKey => $fDef) {
            $defaults[$fKey] = $fDef['default'] ?? null;
        }

        $saved = is_array($page->template_data) ? $page->template_data : [];

        return array_merge($defaults, $saved);
    }
}

