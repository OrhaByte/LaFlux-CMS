<?php

namespace Tests\Feature;

use ExtensionsValley\Cms\Models\Banner;
use ExtensionsValley\Cms\Models\BannerSlide;
use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Models\CmsForm;
use ExtensionsValley\Cms\Models\CmsFormField;
use ExtensionsValley\Cms\Models\ContentVersion;
use ExtensionsValley\Cms\Models\Language;
use ExtensionsValley\Cms\Models\Menu;
use ExtensionsValley\Cms\Models\MenuItem;
use ExtensionsValley\Cms\Models\Page;
use ExtensionsValley\Cms\Models\Post;
use ExtensionsValley\Cms\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_renders_published_posts_pages_categories_tags_and_menus(): void
    {
        $category = Category::create([
            'name' => 'News',
            'slug' => 'news',
            'status' => 1,
        ]);

        $tag = Tag::create([
            'name' => 'Release',
            'slug' => 'release',
            'status' => 1,
        ]);

        $post = Post::create([
            'title' => 'Hello CMS',
            'slug' => 'hello-cms',
            'excerpt' => 'Intro excerpt',
            'content' => '<p>Body content</p>',
            'category_id' => $category->id,
            'meta_title' => 'Hello CMS SEO',
            'status' => 1,
            'published_at' => now()->subMinute(),
        ]);
        $post->tags()->sync([$tag->id]);

        $page = Page::create([
            'title' => 'Contact',
            'slug' => 'contact',
            'content' => '<p>Contact us</p>',
            'status' => 1,
            'published_at' => now()->subMinute(),
        ]);

        $menu = Menu::create([
            'name' => 'Header Menu',
            'slug' => 'header-menu',
            'location' => 'header',
            'status' => 1,
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Contact Link',
            'type' => 'page',
            'reference_id' => $page->id,
            'ordering' => 1,
            'status' => 1,
        ]);

        $this->get('/cms')->assertStatus(200)->assertSee('Hello CMS')->assertSee('Contact Link');
        $this->get('/post/hello-cms')->assertStatus(200)->assertSee('Hello CMS SEO');
        $this->get('/page/contact')->assertStatus(200)->assertSee('Contact us');
        $this->get('/category/news')->assertStatus(200)->assertSee('Hello CMS');
        $this->get('/tag/release')->assertStatus(200)->assertSee('Hello CMS');
    }

    public function test_scheduled_future_post_is_hidden_on_frontend(): void
    {
        Post::create([
            'title' => 'Future Post',
            'slug' => 'future-post',
            'content' => '<p>Not yet live</p>',
            'status' => 1,
            'published_at' => now()->addDay(),
        ]);

        $this->get('/post/future-post')->assertStatus(404);
        $this->get('/cms')->assertStatus(200)->assertDontSee('Future Post');
    }

    public function test_banner_and_form_shortcodes_render_and_form_submits(): void
    {
        $banner = Banner::create(['name' => 'Hero', 'slug' => 'hero', 'status' => 1]);
        BannerSlide::create([
            'banner_id' => $banner->id,
            'title' => 'Big Launch',
            'description' => 'Discover LaFlux CMS',
            'image' => 'uploads/cms/sample.jpg',
            'button_text' => 'Explore',
            'button_link' => '/cms',
            'ordering' => 1,
            'status' => 1,
        ]);

        $form = CmsForm::create([
            'name' => 'Contact',
            'slug' => 'contact',
            'submit_label' => 'Send',
            'success_message' => 'Received!',
            'status' => 1,
        ]);
        CmsFormField::create([
            'form_id' => $form->id,
            'label' => 'Your Email',
            'field_name' => 'email',
            'field_type' => 'email',
            'is_required' => true,
            'ordering' => 1,
        ]);

        Page::create([
            'title' => 'Landing',
            'slug' => 'landing',
            'content' => '<p>[banner slug="hero"]</p><p>[form slug="contact"]</p>',
            'status' => 1,
            'published_at' => now()->subMinute(),
        ]);

        $this->get('/page/landing')
            ->assertStatus(200)
            ->assertSee('Big Launch')
            ->assertSee('Discover LaFlux CMS')
            ->assertSee('Your Email');

        $this->post('/cms/form/contact/submit', ['email' => 'test@example.com'])
            ->assertRedirect()
            ->assertSessionHas('cms_form_success_contact', 'Received!');

        $this->assertDatabaseHas('cms_form_submissions', ['form_id' => $form->id]);
    }

    public function test_multilingual_urls_activate_only_when_multiple_languages_are_active(): void
    {
        // Only default 'en' exists after migration -> single-language mode (no prefix)
        Page::create([
            'language_code' => 'en',
            'title' => 'About English',
            'slug' => 'about',
            'content' => '<p>English body</p>',
            'status' => 1,
            'published_at' => now()->subMinute(),
        ]);

        $this->get('/page/about')->assertStatus(200)->assertSee('English body');
        $this->get('/en/page/about')->assertRedirect('/page/about');

        // Activate a second language ('ar', RTL) -> multilingual mode activates automatically
        Language::create([
            'name' => 'Arabic',
            'code' => 'ar',
            'direction' => 'rtl',
            'is_default' => false,
            'status' => 1,
        ]);

        Page::create([
            'language_code' => 'ar',
            'title' => 'About Arabic',
            'slug' => 'about',
            'content' => '<p>Arabic body</p>',
            'status' => 1,
            'published_at' => now()->subMinute(),
        ]);

        // Unprefixed redirects to default language
        $this->get('/page/about')->assertRedirect('/en/page/about');
        $this->get('/en/page/about')->assertStatus(200)->assertSee('English body')->assertSee('dir="ltr"', false);
        $this->get('/ar/page/about')->assertStatus(200)->assertSee('Arabic body')->assertSee('dir="rtl"', false);
        $this->get('/zz/page/about')->assertStatus(404);
    }

    public function test_content_versioning_records_incrementing_snapshots_for_pages_and_posts(): void
    {
        $page = Page::create([
            'language_code' => 'en',
            'title' => 'Versioned Page v1',
            'slug' => 'versioned-page',
            'content' => '<p>First version</p>',
            'status' => 1,
            'published_at' => now(),
        ]);

        $v1 = ContentVersion::recordSnapshot('page', $page, 1);
        $this->assertSame(1, $v1->version_number);

        $page->update(['title' => 'Versioned Page v2', 'content' => '<p>Second version</p>']);
        $v2 = ContentVersion::recordSnapshot('page', $page, 1);
        $this->assertSame(2, $v2->version_number);

        $versions = $page->versions()->get();
        $this->assertCount(2, $versions);
        $this->assertSame(2, $versions->first()->version_number);
        $this->assertSame('<p>First version</p>', $versions->last()->content);
    }
}

