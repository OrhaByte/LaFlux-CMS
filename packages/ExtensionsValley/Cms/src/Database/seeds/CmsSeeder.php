<?php

namespace ExtensionsValley\Cms\Database\seeds;

use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Models\CmsForm;
use ExtensionsValley\Cms\Models\CmsFormField;
use ExtensionsValley\Cms\Models\Menu;
use ExtensionsValley\Cms\Models\MenuItem;
use ExtensionsValley\Cms\Models\Page;
use ExtensionsValley\Cms\Models\Post;
use ExtensionsValley\Cms\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // 1. Register Cms package in extension_manager
        if (Schema::hasTable('extension_manager')) {
            $exists = DB::table('extension_manager')
                ->where('vendor', 'ExtensionsValley')
                ->where('name', 'Cms')
                ->exists();

            if (!$exists) {
                DB::table('extension_manager')->insert([
                    'name' => 'Cms',
                    'vendor' => 'ExtensionsValley',
                    'description' => 'Mini CMS Package (Menus, Pages, Posts, Categories, Tags)',
                    'version' => '1.0.0',
                    'is_paid' => 0,
                    'status' => 1,
                    'package_type' => 'wwmladmin-package',
                    'icon' => 'packages/extensionsvalley/dashboard/package_icons/icon.png',
                    'update_url' => '',
                    'author' => 'ExtensionsValley',
                    'website' => '',
                    'contact_email' => 'support@walkswithme.net',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // 2. Grant Super Admin (group_id = 1) ACL permissions for CMS menus
        if (Schema::hasTable('acl_permission')) {
            $parentId = DB::table('acl_permission')
                ->where('group_id', 1)
                ->where('acl_key', 'extensionsvalley.cms.panel')
                ->value('id');

            if (!$parentId) {
                $parentId = DB::table('acl_permission')->insertGetId([
                    'group_id' => 1,
                    'menu_text' => 'CMS',
                    'link' => '',
                    'icon' => '<i class="fa fa-newspaper-o"></i>',
                    'acl_key' => 'extensionsvalley.cms.panel',
                    'parent_menu' => 0,
                    'ordering' => 2,
                    'adding' => 0,
                    'edit' => 0,
                    'view' => 1,
                    'trash' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $baseUrl = rtrim(url('/'), '/');
            $subItems = [
                ['menu_text' => 'Posts', 'link' => $baseUrl . '/admin/ExtensionsValley/cms/list/cms_posts', 'acl_key' => 'extensionsvalley.cms.posts', 'ordering' => 1],
                ['menu_text' => 'Pages', 'link' => $baseUrl . '/admin/ExtensionsValley/cms/list/cms_pages', 'acl_key' => 'extensionsvalley.cms.pages', 'ordering' => 2],
                ['menu_text' => 'Categories', 'link' => $baseUrl . '/admin/ExtensionsValley/cms/list/cms_categories', 'acl_key' => 'extensionsvalley.cms.categories', 'ordering' => 3],
                ['menu_text' => 'Tags', 'link' => $baseUrl . '/admin/ExtensionsValley/cms/list/cms_tags', 'acl_key' => 'extensionsvalley.cms.tags', 'ordering' => 4],
                ['menu_text' => 'Menus', 'link' => $baseUrl . '/admin/ExtensionsValley/cms/list/cms_menus', 'acl_key' => 'extensionsvalley.cms.menus', 'ordering' => 5],
                ['menu_text' => 'Media Library', 'link' => $baseUrl . '/admin/cms/media', 'acl_key' => 'extensionsvalley.cms.media', 'ordering' => 6],
                ['menu_text' => 'Banners', 'link' => $baseUrl . '/admin/ExtensionsValley/cms/list/cms_banners', 'acl_key' => 'extensionsvalley.cms.banners', 'ordering' => 7],
                ['menu_text' => 'Forms', 'link' => $baseUrl . '/admin/ExtensionsValley/cms/list/cms_forms', 'acl_key' => 'extensionsvalley.cms.forms', 'ordering' => 8],
                ['menu_text' => 'Languages', 'link' => $baseUrl . '/admin/ExtensionsValley/cms/list/cms_languages', 'acl_key' => 'extensionsvalley.cms.languages', 'ordering' => 9],
                ['menu_text' => 'Theme Customizer', 'link' => $baseUrl . '/admin/cms/customizer', 'acl_key' => 'extensionsvalley.cms.customizer', 'ordering' => 10],
            ];

            foreach ($subItems as $item) {
                $exists = DB::table('acl_permission')
                    ->where('group_id', 1)
                    ->where('acl_key', $item['acl_key'])
                    ->exists();

                if (!$exists) {
                    DB::table('acl_permission')->insert([
                        'group_id' => 1,
                        'menu_text' => $item['menu_text'],
                        'link' => $item['link'],
                        'icon' => '',
                        'acl_key' => $item['acl_key'],
                        'parent_menu' => $parentId,
                        'ordering' => $item['ordering'],
                        'adding' => 1,
                        'edit' => 1,
                        'view' => 1,
                        'trash' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        // 3. Seed starter CMS data if empty
        if (Category::count() === 0) {
            $category = Category::create([
                'name' => 'General',
                'slug' => 'general',
                'description' => 'Default blog category',
                'meta_title' => 'General',
                'meta_description' => 'General articles and updates.',
                'status' => 1,
                'created_by' => 1,
            ]);

            $tag = Tag::create([
                'name' => 'Laravel',
                'slug' => 'laravel',
                'status' => 1,
            ]);

            $post = Post::create([
                'title' => 'Welcome to Your Mini CMS',
                'slug' => 'welcome-to-your-mini-cms',
                'excerpt' => 'Your Laravel 13 Mini CMS is up and running.',
                'content' => '<p>Welcome! You can manage <strong>Posts</strong>, <strong>Pages</strong>, <strong>Categories</strong>, <strong>Tags</strong>, and <strong>Menus</strong> right from the admin panel.</p>',
                'category_id' => $category->id,
                'meta_title' => 'Welcome to Your Mini CMS',
                'meta_description' => 'Getting started with the Laravel 13 Mini CMS package.',
                'status' => 1,
                'published_at' => $now,
                'author_id' => 1,
            ]);
            $post->tags()->sync([$tag->id]);

            $aboutPage = Page::create([
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<p>This is a sample CMS page. Edit or delete it from <strong>CMS &rarr; Pages</strong> in the admin dashboard.</p>',
                'meta_title' => 'About Us',
                'meta_description' => 'Learn more about our site.',
                'ordering' => 1,
                'status' => 1,
                'published_at' => $now,
                'author_id' => 1,
            ]);

            $headerMenu = Menu::create([
                'name' => 'Main Navigation',
                'slug' => 'main-navigation',
                'location' => 'header',
                'status' => 1,
            ]);

            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'title' => 'Home',
                'type' => 'custom',
                'url' => '/cms',
                'target' => '_self',
                'ordering' => 1,
                'status' => 1,
            ]);

            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'title' => 'About Us',
                'type' => 'page',
                'reference_id' => $aboutPage->id,
                'target' => '_self',
                'ordering' => 2,
                'status' => 1,
            ]);

            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'title' => 'General',
                'type' => 'category',
                'reference_id' => $category->id,
                'target' => '_self',
                'ordering' => 3,
                'status' => 1,
            ]);
        }

        // 4. Seed starter Contact Us form if empty
        if (Schema::hasTable('cms_forms') && CmsForm::count() === 0) {
            $contactForm = CmsForm::create([
                'name' => 'Contact Us',
                'slug' => 'contact-us',
                'submit_label' => 'Send Message',
                'success_message' => 'Thank you for contacting us! We will get back to you shortly.',
                'status' => 1,
            ]);

            $fields = [
                ['label' => 'Full Name', 'field_name' => 'full_name', 'field_type' => 'text', 'placeholder' => 'Your name', 'is_required' => true, 'ordering' => 1],
                ['label' => 'Email Address', 'field_name' => 'email', 'field_type' => 'email', 'placeholder' => 'you@example.com', 'is_required' => true, 'ordering' => 2],
                ['label' => 'Subject', 'field_name' => 'subject', 'field_type' => 'select', 'placeholder' => 'Choose a topic', 'options' => 'General Inquiry, Support, Sales', 'is_required' => true, 'ordering' => 3],
                ['label' => 'Message', 'field_name' => 'message', 'field_type' => 'textarea', 'placeholder' => 'How can we help?', 'is_required' => true, 'ordering' => 4],
            ];
            foreach ($fields as $f) {
                CmsFormField::create(array_merge($f, ['form_id' => $contactForm->id]));
            }
        }
    }
}
