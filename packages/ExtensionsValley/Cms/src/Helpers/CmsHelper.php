<?php

namespace ExtensionsValley\Cms\Helpers;

use ExtensionsValley\Cms\Models\Language;
use ExtensionsValley\Cms\Models\Media;
use ExtensionsValley\Cms\Models\Menu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CmsHelper
{
    public const UPLOAD_BASE = 'uploads/cms';

    public static function uniqueSlug(string $table, string $source, ?int $ignoreId = null, ?string $languageCode = null): string
    {
        $base = Str::slug($source) ?: 'item';
        $slug = $base;
        $counter = 1;

        while (
            DB::table($table)
                ->where('slug', $slug)
                ->when($languageCode !== null && Schema::hasColumn($table, 'language_code'), fn ($q) => $q->where('language_code', $languageCode))
                ->when($ignoreId, fn ($q) => $q->where('id', '<>', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    public static function activeLanguages(): Collection
    {
        if (!Schema::hasTable('cms_languages')) {
            return collect([
                (object) ['name' => 'English', 'code' => 'en', 'direction' => 'ltr', 'is_default' => true, 'status' => 1],
            ]);
        }

        $langs = Language::where('status', 1)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        if ($langs->isEmpty()) {
            return collect([
                (object) ['name' => 'English', 'code' => 'en', 'direction' => 'ltr', 'is_default' => true, 'status' => 1],
            ]);
        }

        return $langs;
    }

    public static function isMultilingual(): bool
    {
        return self::activeLanguages()->count() > 1;
    }

    public static function defaultLanguageCode(): string
    {
        $langs = self::activeLanguages();
        $default = $langs->firstWhere('is_default', true) ?? $langs->first();

        return strtolower((string) ($default->code ?? 'en'));
    }

    public static function currentLanguage(): object
    {
        $langs = self::activeLanguages();
        $locale = strtolower((string) app()->getLocale());

        return $langs->firstWhere('code', $locale)
            ?? $langs->firstWhere('is_default', true)
            ?? $langs->first();
    }

    public static function currentLanguageCode(): string
    {
        return strtolower((string) (self::currentLanguage()->code ?? 'en'));
    }

    /**
     * Build a front-end CMS URL:
     * - Single active language: /cms, /page/about-us, /post/hello
     * - Multiple active languages: /en/cms, /ar/page/about-us, /es/post/hello
     */
    public static function url(string $path = '', ?string $lang = null): string
    {
        $cleanPath = ltrim($path, '/');

        if (!self::isMultilingual()) {
            return $cleanPath === '' ? url('/') : url('/' . $cleanPath);
        }

        $code = strtolower($lang ?: self::currentLanguageCode());

        return $cleanPath === '' ? url('/' . $code) : url('/' . $code . '/' . $cleanPath);
    }

    public static function storeMediaFile(UploadedFile $file, ?int $userId = null): Media
    {
        $relativeDir = self::UPLOAD_BASE . '/' . date('Y/m');
        $targetDir = public_path($relativeDir);
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $mime = $file->getClientMimeType();
        $size = $file->getSize();
        $storedName = time() . '_' . Str::random(8) . '.' . $extension;

        $file->move($targetDir, $storedName);

        return Media::create([
            'title' => pathinfo($originalName, PATHINFO_FILENAME),
            'alt_text' => pathinfo($originalName, PATHINFO_FILENAME),
            'file_name' => $originalName,
            'file_path' => $relativeDir . '/' . $storedName,
            'mime_type' => $mime,
            'file_size' => $size,
            'uploaded_by' => $userId ?? Auth::guard('admin')->id(),
        ]);
    }

    public static function uploadFeaturedImage(?UploadedFile $file, string $folder = 'cms'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        return self::storeMediaFile($file)->file_path;
    }

    public static function sanitizeMediaPath(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $clean = ltrim(str_replace('\\', '/', trim($path)), '/');
        if (str_contains($clean, '..')) {
            return null;
        }

        if (str_starts_with($clean, self::UPLOAD_BASE . '/') || str_starts_with($clean, 'themes/')) {
            return $clean;
        }

        return null;
    }

    public static function sanitizeUrl(?string $url): string
    {
        $trimmed = trim((string) $url);
        if ($trimmed === '') {
            return '#';
        }

        if (preg_match('/^\s*(javascript|data|vbscript):/i', $trimmed)) {
            return '#';
        }

        return $trimmed;
    }

    public static function hasAcl(string $aclKey, string $action = 'view'): bool
    {
        $user = Auth::guard('admin')->user();
        if (!$user) {
            return false;
        }
        if ((int) $user->groups === 1) {
            return true;
        }
        if (!Schema::hasTable('acl_permission')) {
            return true;
        }

        $col = in_array($action, ['view', 'adding', 'edit', 'trash'], true) ? $action : 'view';

        return DB::table('acl_permission')
            ->where('group_id', $user->groups)
            ->where('acl_key', $aclKey)
            ->where($col, 1)
            ->exists();
    }

    public static function getMenuByLocation(string $location, ?string $lang = null): ?Menu
    {
        $code = strtolower($lang ?: self::currentLanguageCode());

        $menu = Menu::where('location', $location)
            ->where('language_code', $code)
            ->where('status', 1)
            ->with(['rootItems.children'])
            ->first();

        if (!$menu && $code !== self::defaultLanguageCode()) {
            $menu = Menu::where('location', $location)
                ->where('language_code', self::defaultLanguageCode())
                ->where('status', 1)
                ->with(['rootItems.children'])
                ->first();
        }

        return $menu;
    }
}
