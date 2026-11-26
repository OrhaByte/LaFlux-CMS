<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Helpers\ThemeHelper;
use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Models\CmsForm;
use ExtensionsValley\Cms\Models\CmsFormSubmission;
use ExtensionsValley\Cms\Models\Page;
use ExtensionsValley\Cms\Models\Post;
use ExtensionsValley\Cms\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    protected function resolveLanguage(?string $lang = null): string
    {
        $active = CmsHelper::activeLanguages();
        $code = strtolower($lang ?: CmsHelper::defaultLanguageCode());

        if ($lang !== null && !$active->contains('code', $code)) {
            abort(404);
        }

        app()->setLocale($code);

        return $code;
    }

    protected function sharedData(string $lang): array
    {
        $languages = CmsHelper::activeLanguages();
        $currentLang = $languages->firstWhere('code', $lang) ?? $languages->first();

        return [
            'currentLang' => $currentLang,
            'activeLanguages' => $languages,
            'isMultilingual' => $languages->count() > 1,
            'headerMenu' => CmsHelper::getMenuByLocation('header', $lang),
            'footerMenu' => CmsHelper::getMenuByLocation('footer', $lang),
            'sidebarCategories' => Category::where('status', 1)->where('language_code', $lang)->orderBy('name')->get(),
            'sidebarTags' => Tag::where('status', 1)->where('language_code', $lang)->orderBy('name')->get(),
        ];
    }

    // --- Unprefixed entry points ---

    public function root()
    {
        if (CmsHelper::isMultilingual()) {
            return redirect(CmsHelper::url('', CmsHelper::defaultLanguageCode()));
        }

        return $this->renderHome($this->resolveLanguage());
    }

    public function home()
    {
        if (CmsHelper::isMultilingual()) {
            return redirect(CmsHelper::url('cms', CmsHelper::defaultLanguageCode()));
        }

        return $this->renderHome($this->resolveLanguage());
    }

    public function page(string $slug)
    {
        if (CmsHelper::isMultilingual()) {
            return redirect(CmsHelper::url('page/' . $slug, CmsHelper::defaultLanguageCode()));
        }

        return $this->renderPage($this->resolveLanguage(), $slug);
    }

    public function post(string $slug)
    {
        if (CmsHelper::isMultilingual()) {
            return redirect(CmsHelper::url('post/' . $slug, CmsHelper::defaultLanguageCode()));
        }

        return $this->renderPost($this->resolveLanguage(), $slug);
    }

    public function category(string $slug)
    {
        if (CmsHelper::isMultilingual()) {
            return redirect(CmsHelper::url('category/' . $slug, CmsHelper::defaultLanguageCode()));
        }

        return $this->renderCategory($this->resolveLanguage(), $slug);
    }

    public function tag(string $slug)
    {
        if (CmsHelper::isMultilingual()) {
            return redirect(CmsHelper::url('tag/' . $slug, CmsHelper::defaultLanguageCode()));
        }

        return $this->renderTag($this->resolveLanguage(), $slug);
    }

    // --- Language-prefixed entry points (/{lang}/...) ---

    public function homeLang(string $lang)
    {
        $code = $this->resolveLanguage($lang);
        if (!CmsHelper::isMultilingual()) {
            return redirect(url('/cms'));
        }

        return $this->renderHome($code);
    }

    public function pageLang(string $lang, string $slug)
    {
        $code = $this->resolveLanguage($lang);
        if (!CmsHelper::isMultilingual()) {
            return redirect(url('/page/' . $slug));
        }

        return $this->renderPage($code, $slug);
    }

    public function postLang(string $lang, string $slug)
    {
        $code = $this->resolveLanguage($lang);
        if (!CmsHelper::isMultilingual()) {
            return redirect(url('/post/' . $slug));
        }

        return $this->renderPost($code, $slug);
    }

    public function categoryLang(string $lang, string $slug)
    {
        $code = $this->resolveLanguage($lang);
        if (!CmsHelper::isMultilingual()) {
            return redirect(url('/category/' . $slug));
        }

        return $this->renderCategory($code, $slug);
    }

    public function tagLang(string $lang, string $slug)
    {
        $code = $this->resolveLanguage($lang);
        if (!CmsHelper::isMultilingual()) {
            return redirect(url('/tag/' . $slug));
        }

        return $this->renderTag($code, $slug);
    }

    // --- Shared renderers ---

    protected function renderHome(string $lang)
    {
        $posts = Post::published()
            ->where('language_code', $lang)
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('Theme::home', array_merge($this->sharedData($lang), [
            'posts' => $posts,
            'metaTitle' => 'Home',
            'metaDescription' => \WebConf::get('meta_desc') ?: 'Modern modular Laravel 13 CMS.',
        ]));
    }

    protected function renderPage(string $lang, string $slug)
    {
        $page = Page::published()
            ->where('language_code', $lang)
            ->with('author')
            ->where('slug', $slug)
            ->firstOrFail();

        $viewName = ThemeHelper::resolvePageTemplateView($page->template);
        $templateData = ThemeHelper::getPageTemplateData($page);

        return view($viewName, array_merge($this->sharedData($lang), [
            'page' => $page,
            'templateData' => $templateData,
            'metaTitle' => $page->meta_title ?: $page->title,
            'metaKeywords' => $page->meta_keywords ?: '',
            'metaDescription' => $page->meta_description ?: '',
        ]));
    }

    protected function renderPost(string $lang, string $slug)
    {
        $post = Post::published()
            ->where('language_code', $lang)
            ->with(['category', 'tags', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('Theme::post', array_merge($this->sharedData($lang), [
            'post' => $post,
            'metaTitle' => $post->meta_title ?: $post->title,
            'metaKeywords' => $post->meta_keywords ?: '',
            'metaDescription' => $post->meta_description ?: ($post->excerpt ?? ''),
        ]));
    }

    protected function renderCategory(string $lang, string $slug)
    {
        $category = Category::where('status', 1)
            ->where('language_code', $lang)
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = Post::published()
            ->where('language_code', $lang)
            ->where('category_id', $category->id)
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('Theme::category', array_merge($this->sharedData($lang), [
            'category' => $category,
            'posts' => $posts,
            'metaTitle' => $category->meta_title ?: $category->name,
            'metaKeywords' => $category->meta_keywords ?: '',
            'metaDescription' => $category->meta_description ?: ($category->description ?? ''),
        ]));
    }

    protected function renderTag(string $lang, string $slug)
    {
        $tag = Tag::where('status', 1)
            ->where('language_code', $lang)
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = $tag->posts()
            ->published()
            ->where('language_code', $lang)
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('Theme::tag', array_merge($this->sharedData($lang), [
            'tag' => $tag,
            'posts' => $posts,
            'metaTitle' => 'Tag: ' . $tag->name,
            'metaDescription' => 'Posts tagged with ' . $tag->name,
        ]));
    }

    public function submitForm(Request $request, string $slug)
    {
        $form = CmsForm::where('status', 1)
            ->with('fields')
            ->where('slug', $slug)
            ->firstOrFail();

        $rules = [];
        $payload = [];

        foreach ($form->fields as $field) {
            $fieldRules = [];
            $fieldRules[] = $field->is_required ? 'required' : 'nullable';

            if ($field->field_type === 'email') {
                $fieldRules[] = 'email';
            } elseif ($field->field_type === 'number') {
                $fieldRules[] = 'numeric';
            } elseif ($field->field_type === 'date') {
                $fieldRules[] = 'date';
            } elseif ($field->field_type === 'checkbox') {
                $fieldRules[] = 'array';
            }

            $rules[$field->field_name] = $fieldRules;
            $payload[$field->field_name] = $request->input($field->field_name);
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'cms_form_' . $form->slug)
                ->withInput();
        }

        // Verify Google reCAPTCHA v2 if keys are configured in General Settings
        $recaptchaSiteKey = trim((string) \WebConf::get('recaptcha_site_key'));
        $recaptchaSecretKey = trim((string) \WebConf::get('recaptcha_secret_key'));
        if ($recaptchaSiteKey !== '' && $recaptchaSecretKey !== '') {
            $token = (string) $request->input('g-recaptcha-response', '');
            if ($token === '') {
                return redirect()->back()
                    ->withErrors(['captcha' => 'Please complete the reCAPTCHA verification.'], 'cms_form_' . $form->slug)
                    ->withInput();
            }

            try {
                $verifyResponse = Http::asForm()->timeout(8)->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptchaSecretKey,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);
                $verified = (bool) data_get($verifyResponse->json(), 'success', false);
            } catch (\Throwable $e) {
                $verified = false;
            }

            if (!$verified) {
                return redirect()->back()
                    ->withErrors(['captcha' => 'reCAPTCHA verification failed. Please try again.'], 'cms_form_' . $form->slug)
                    ->withInput();
            }
        }

        CmsFormSubmission::create([
            'form_id' => $form->id,
            'payload' => $payload,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return redirect()->back()
            ->with('cms_form_success_' . $form->slug, $form->success_message ?: 'Thank you! Your submission has been received.');
    }
}
