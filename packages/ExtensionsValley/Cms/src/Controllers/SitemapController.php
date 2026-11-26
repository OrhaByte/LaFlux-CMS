<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Models\Page;
use ExtensionsValley\Cms\Models\Post;
use ExtensionsValley\Cms\Models\Tag;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SitemapController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function buildEntries(): array
    {
        $languages = CmsHelper::activeLanguages();
        $entries = [];
        $stats = [];

        foreach ($languages as $lang) {
            $code = strtolower($lang->code);
            $langEntries = [];

            // Homepage
            $langEntries[] = [
                'loc' => CmsHelper::url('', $code),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];

            // Published Pages
            $pages = Page::published()->where('language_code', $code)->orderBy('ordering')->get();
            foreach ($pages as $page) {
                $langEntries[] = [
                    'loc' => CmsHelper::url('page/' . $page->slug, $code),
                    'lastmod' => ($page->updated_at ?? $page->created_at ?? now())->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }

            // Published Posts
            $posts = Post::published()->where('language_code', $code)->orderByDesc('published_at')->get();
            foreach ($posts as $post) {
                $langEntries[] = [
                    'loc' => CmsHelper::url('post/' . $post->slug, $code),
                    'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }

            // Active Categories
            $categories = Category::where('status', 1)->where('language_code', $code)->get();
            foreach ($categories as $cat) {
                $langEntries[] = [
                    'loc' => CmsHelper::url('category/' . $cat->slug, $code),
                    'lastmod' => ($cat->updated_at ?? $cat->created_at ?? now())->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            }

            // Active Tags
            $tags = Tag::where('status', 1)->where('language_code', $code)->get();
            foreach ($tags as $tag) {
                $langEntries[] = [
                    'loc' => CmsHelper::url('tag/' . $tag->slug, $code),
                    'lastmod' => ($tag->updated_at ?? $tag->created_at ?? now())->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.5',
                ];
            }

            $stats[] = [
                'name' => $lang->name,
                'code' => strtoupper($code),
                'pages' => $pages->count(),
                'posts' => $posts->count(),
                'categories' => $categories->count(),
                'tags' => $tags->count(),
                'total' => count($langEntries),
            ];

            $entries = array_merge($entries, $langEntries);
        }

        return ['entries' => $entries, 'stats' => $stats];
    }

    public function buildXml(array $entries): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($entries as $entry) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . htmlspecialchars($entry['lastmod'], ENT_XML1, 'UTF-8') . "</lastmod>\n";
            $xml .= '    <changefreq>' . htmlspecialchars($entry['changefreq'], ENT_XML1, 'UTF-8') . "</changefreq>\n";
            $xml .= '    <priority>' . htmlspecialchars($entry['priority'], ENT_XML1, 'UTF-8') . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>\n";

        return $xml;
    }

    public function publicXml()
    {
        $data = $this->buildEntries();

        return response($this->buildXml($data['entries']), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function index()
    {
        if (!CmsHelper::hasAcl('extensionsvalley.cms.sitemap', 'view')) {
            return redirect()->route('extensionsvalley.admin.dashboard')->with(['error' => 'Access Permission Denied!']);
        }

        $title = 'Multilingual XML Sitemap';
        $data = $this->buildEntries();
        $entries = $data['entries'];
        $stats = $data['stats'];
        $xmlPreview = $this->buildXml($entries);
        $staticFile = public_path('sitemap.xml');
        $staticExists = is_file($staticFile);
        $staticUpdated = $staticExists ? date('M j, Y H:i', filemtime($staticFile)) : null;

        return view('Cms::admin.sitemap.index', compact(
            'title',
            'entries',
            'stats',
            'xmlPreview',
            'staticExists',
            'staticUpdated'
        ));
    }

    public function generate(Request $request)
    {
        if (!CmsHelper::hasAcl('extensionsvalley.cms.sitemap', 'edit')) {
            return redirect()->route('extensionsvalley.admin.dashboard')->with(['error' => 'Access Permission Denied!']);
        }

        $data = $this->buildEntries();
        $xml = $this->buildXml($data['entries']);

        $target = public_path('sitemap.xml');
        if (@file_put_contents($target, $xml) === false) {
            return redirect()->route('extensionsvalley.cms.sitemap')
                ->with(['error' => 'Unable to write public/sitemap.xml (check directory permissions). Dynamic /sitemap.xml is still active!']);
        }

        return redirect()->route('extensionsvalley.cms.sitemap')
            ->with(['message' => 'Generated public/sitemap.xml with ' . count($data['entries']) . ' URLs across all active languages!']);
    }
}

