<?php

namespace ExtensionsValley\Cms\Database\seeds;

use ExtensionsValley\Cms\Models\Banner;
use ExtensionsValley\Cms\Models\BannerSlide;
use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Models\CmsForm;
use ExtensionsValley\Cms\Models\CmsFormField;
use ExtensionsValley\Cms\Models\Language;
use ExtensionsValley\Cms\Models\Menu;
use ExtensionsValley\Cms\Models\MenuItem;
use ExtensionsValley\Cms\Models\Page;
use ExtensionsValley\Cms\Models\Post;
use ExtensionsValley\Cms\Models\Tag;
use Illuminate\Database\Seeder;

class ThemeDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // 1. Languages (English LTR default + Arabic RTL)
        Language::updateOrCreate(
            ['code' => 'en'],
            ['name' => 'English', 'direction' => 'ltr', 'is_default' => true, 'status' => 1]
        );
        Language::updateOrCreate(
            ['code' => 'ar'],
            ['name' => 'العربية', 'direction' => 'rtl', 'is_default' => false, 'status' => 1]
        );

        // 2. Homepage Hero Slider ([banner slug="home-hero"])
        $heroBanner = Banner::updateOrCreate(
            ['slug' => 'home-hero'],
            ['name' => 'Homepage Hero Slider', 'status' => 1]
        );

        if ($heroBanner->slides()->count() === 0) {
            $slides = [
                [
                    'title' => 'Build Modern Websites with LaFlux CMS',
                    'description' => 'Powered by Laravel 13 & OrhaByte Software Labs LLP — fast, modular, and multilingual out of the box.',
                    'image' => 'themes/default/images/hero-1.svg',
                    'button_text' => 'Explore Services',
                    'button_link' => '/en/page/services',
                    'ordering' => 1,
                ],
                [
                    'title' => 'Shortcodes, Sliders & Dynamic Forms',
                    'description' => 'Compose landing pages with reusable hero sliders, custom forms, and a WordPress-style media library.',
                    'image' => 'themes/default/images/hero-2.svg',
                    'button_text' => 'About LaFlux',
                    'button_link' => '/en/page/about-us',
                    'ordering' => 2,
                ],
                [
                    'title' => 'Multilingual Publishing Made Effortless',
                    'description' => 'Serve English, Arabic (RTL), and any global audience with automatic 2-letter language routing.',
                    'image' => 'themes/default/images/hero-3.svg',
                    'button_text' => 'Contact Us',
                    'button_link' => '/en/page/contact-us',
                    'ordering' => 3,
                ],
            ];
            foreach ($slides as $s) {
                BannerSlide::create(array_merge($s, ['banner_id' => $heroBanner->id, 'status' => 1]));
            }
        }

        // 3. Contact Form ([form slug="contact-us"])
        $contactForm = CmsForm::updateOrCreate(
            ['slug' => 'contact-us'],
            [
                'name' => 'Contact Us',
                'submit_label' => 'Send Message',
                'success_message' => 'Thank you! We received your message and will respond shortly.',
                'status' => 1,
            ]
        );
        if ($contactForm->fields()->count() === 0) {
            $fields = [
                ['label' => 'Full Name', 'field_name' => 'full_name', 'field_type' => 'text', 'placeholder' => 'Your full name', 'is_required' => true, 'ordering' => 1],
                ['label' => 'Email Address', 'field_name' => 'email', 'field_type' => 'email', 'placeholder' => 'you@example.com', 'is_required' => true, 'ordering' => 2],
                ['label' => 'Topic', 'field_name' => 'topic', 'field_type' => 'select', 'placeholder' => 'Select a topic', 'options' => 'Project Inquiry, Support, Partnership', 'is_required' => true, 'ordering' => 3],
                ['label' => 'Message', 'field_name' => 'message', 'field_type' => 'textarea', 'placeholder' => 'Tell us about your project...', 'is_required' => true, 'ordering' => 4],
            ];
            foreach ($fields as $f) {
                CmsFormField::create(array_merge($f, ['form_id' => $contactForm->id]));
            }
        }

        // 4. Categories (EN & AR)
        $enTech = Category::updateOrCreate(
            ['slug' => 'technology', 'language_code' => 'en'],
            ['name' => 'Technology', 'description' => 'Laravel, PHP 8.4, and modern web engineering.', 'meta_title' => 'Technology', 'status' => 1, 'created_by' => 1]
        );
        $enDesign = Category::updateOrCreate(
            ['slug' => 'design', 'language_code' => 'en'],
            ['name' => 'Design', 'description' => 'UI/UX design systems and responsive layouts.', 'meta_title' => 'Design', 'status' => 1, 'created_by' => 1]
        );
        $enBiz = Category::updateOrCreate(
            ['slug' => 'business', 'language_code' => 'en'],
            ['name' => 'Business', 'description' => 'Digital strategy and product growth.', 'meta_title' => 'Business', 'status' => 1, 'created_by' => 1]
        );

        $arTech = Category::updateOrCreate(
            ['slug' => 'technology', 'language_code' => 'ar'],
            ['name' => 'التقنية', 'description' => 'مقالات حول لارافيل وتطوير الويب الحديث.', 'meta_title' => 'التقنية', 'status' => 1, 'created_by' => 1]
        );
        $arDesign = Category::updateOrCreate(
            ['slug' => 'design', 'language_code' => 'ar'],
            ['name' => 'التصميم', 'description' => 'تصميم واجهات المستخدم وتجربة الاستخدام.', 'meta_title' => 'التصميم', 'status' => 1, 'created_by' => 1]
        );

        // 5. Tags (EN & AR)
        $enTagLaravel = Tag::updateOrCreate(['slug' => 'laravel', 'language_code' => 'en'], ['name' => 'Laravel', 'status' => 1]);
        $enTagCms = Tag::updateOrCreate(['slug' => 'cms', 'language_code' => 'en'], ['name' => 'CMS', 'status' => 1]);
        $enTagUi = Tag::updateOrCreate(['slug' => 'ui-ux', 'language_code' => 'en'], ['name' => 'UI/UX', 'status' => 1]);

        $arTagLaravel = Tag::updateOrCreate(['slug' => 'laravel', 'language_code' => 'ar'], ['name' => 'لارافيل', 'status' => 1]);
        $arTagCms = Tag::updateOrCreate(['slug' => 'cms', 'language_code' => 'ar'], ['name' => 'إدارة المحتوى', 'status' => 1]);

        // 6. Pages (EN & AR)
        $enAbout = Page::updateOrCreate(
            ['slug' => 'about-us', 'language_code' => 'en'],
            [
                'title' => 'About Us',
                'content' => '<h2>Who We Are</h2><p>LaFlux CMS is crafted by <strong>OrhaByte Software Labs LLP</strong> to give teams a fast, modular, Laravel 13 publishing platform without unnecessary complexity.</p><p>Manage posts, pages, categories, tags, multilingual menus, sliders, media, and custom forms from one unified dashboard.</p>',
                'featured_image' => 'themes/default/images/hero-1.svg',
                'meta_title' => 'About Us | LaFlux CMS',
                'meta_keywords' => 'LaFlux CMS, OrhaByte, Laravel 13 CMS',
                'meta_description' => 'Learn more about LaFlux CMS powered by OrhaByte Software Labs LLP.',
                'ordering' => 1,
                'status' => 1,
                'published_at' => $now,
                'author_id' => 1,
            ]
        );
        $enServices = Page::updateOrCreate(
            ['slug' => 'services', 'language_code' => 'en'],
            [
                'title' => 'Our Services',
                'content' => '<h2>What We Build</h2><ul><li><strong>Custom Laravel Platforms:</strong> Scalable enterprise web apps built on Laravel 13.</li><li><strong>Multilingual CMS Solutions:</strong> Full LTR &amp; RTL publishing with clean SEO URLs.</li><li><strong>UI/UX &amp; Theme Engineering:</strong> Fast, responsive Blade themes tailored to your brand.</li></ul>',
                'featured_image' => 'themes/default/images/hero-2.svg',
                'meta_title' => 'Services | LaFlux CMS',
                'meta_keywords' => 'Laravel services, CMS development, web design',
                'meta_description' => 'Explore our web engineering, CMS, and theme design services.',
                'ordering' => 2,
                'status' => 1,
                'published_at' => $now,
                'author_id' => 1,
            ]
        );
        $enContact = Page::updateOrCreate(
            ['slug' => 'contact-us', 'language_code' => 'en'],
            [
                'title' => 'Contact Us',
                'content' => '<p>Have a question or want to start a project? Fill out the form below and our team will get back to you.</p><p>[form slug="contact-us"]</p>',
                'meta_title' => 'Contact Us | LaFlux CMS',
                'meta_keywords' => 'contact, support, LaFlux CMS',
                'meta_description' => 'Get in touch with our team.',
                'ordering' => 3,
                'status' => 1,
                'published_at' => $now,
                'author_id' => 1,
            ]
        );

        $arAbout = Page::updateOrCreate(
            ['slug' => 'about-us', 'language_code' => 'ar'],
            [
                'title' => 'من نحن',
                'content' => '<h2>عن نظام LaFlux CMS</h2><p>نظام إدارة محتوى حديث مبني على <strong>Laravel 13</strong> بواسطة <strong>OrhaByte Software Labs LLP</strong>، يدعم تعدد اللغات والاتجاه من اليمين إلى اليسار (RTL) بكل سهولة.</p>',
                'featured_image' => 'themes/default/images/hero-1.svg',
                'meta_title' => 'من نحن | LaFlux CMS',
                'meta_keywords' => 'لارافيل, نظام إدارة محتوى, LaFlux',
                'meta_description' => 'تعرف على نظام LaFlux CMS متعدد اللغات.',
                'ordering' => 1,
                'status' => 1,
                'published_at' => $now,
                'author_id' => 1,
            ]
        );
        $arServices = Page::updateOrCreate(
            ['slug' => 'services', 'language_code' => 'ar'],
            [
                'title' => 'خدماتنا',
                'content' => '<h2>ماذا نقدم؟</h2><p>نطور منصات ويب سريعة، قوالب احترافية، وحلول إدارة محتوى مخصصة للأعمال الحديثة.</p>',
                'featured_image' => 'themes/default/images/hero-2.svg',
                'meta_title' => 'خدماتنا | LaFlux CMS',
                'meta_description' => 'استكشف خدمات تطوير الويب وإدارة المحتوى.',
                'ordering' => 2,
                'status' => 1,
                'published_at' => $now,
                'author_id' => 1,
            ]
        );
        $arContact = Page::updateOrCreate(
            ['slug' => 'contact-us', 'language_code' => 'ar'],
            [
                'title' => 'اتصل بنا',
                'content' => '<p>يسعدنا تواصلك معنا عبر النموذج التالي:</p><p>[form slug="contact-us"]</p>',
                'meta_title' => 'اتصل بنا | LaFlux CMS',
                'meta_description' => 'تواصل مع فريقنا.',
                'ordering' => 3,
                'status' => 1,
                'published_at' => $now,
                'author_id' => 1,
            ]
        );

        // 7. Sample Posts (3 EN + 3 AR)
        $enPosts = [
            [
                'slug' => 'introducing-laflux-cms-laravel-13',
                'title' => 'Introducing LaFlux CMS on Laravel 13',
                'excerpt' => 'Discover how LaFlux combines Laravel 13 speed with WordPress-style content management, media library, and shortcodes.',
                'content' => '<p>LaFlux CMS brings a clean, package-driven CMS architecture directly into Laravel 13. With built-in ACL, TinyMCE 6 editing, scheduled publishing, and dynamic forms, teams can launch sites in minutes.</p><h3>Highlights</h3><ul><li>Modular package structure</li><li>Drag-and-drop menus and banners</li><li>Built-in SEO meta management</li></ul>',
                'category_id' => $enTech->id,
                'featured_image' => 'themes/default/images/post-1.svg',
                'tags' => [$enTagLaravel->id, $enTagCms->id],
            ],
            [
                'slug' => 'designing-multipurpose-blade-themes',
                'title' => 'Designing Multi-Purpose Blade Themes',
                'excerpt' => 'How to organize reusable Blade layouts, responsive CSS variables, and RTL support for global websites.',
                'content' => '<p>With a dedicated <code>resources/themes/default</code> folder, your front-end theme stays cleanly separated from core CMS packages. Customize styles, layouts, and partials without touching vendor code.</p>',
                'category_id' => $enDesign->id,
                'featured_image' => 'themes/default/images/post-2.svg',
                'tags' => [$enTagUi->id, $enTagCms->id],
            ],
            [
                'slug' => 'scaling-multilingual-content-workflows',
                'title' => 'Scaling Multilingual Content Workflows',
                'excerpt' => 'Publish localized pages, posts, and navigation menus with automatic 2-letter language URLs and RTL layouts.',
                'content' => '<p>Adding a second language in <strong>CMS &rarr; Languages</strong> automatically activates clean <code>/en/</code> and <code>/ar/</code> routes across your entire front end—and switches back to clean unprefixed URLs whenever only one language is active.</p>',
                'category_id' => $enBiz->id,
                'featured_image' => 'themes/default/images/post-3.svg',
                'tags' => [$enTagLaravel->id, $enTagCms->id],
            ],
        ];

        foreach ($enPosts as $i => $data) {
            $tagIds = $data['tags'];
            unset($data['tags']);
            $post = Post::updateOrCreate(
                ['slug' => $data['slug'], 'language_code' => 'en'],
                array_merge($data, [
                    'meta_title' => $data['title'],
                    'meta_keywords' => 'LaFlux, Laravel 13, CMS',
                    'meta_description' => $data['excerpt'],
                    'status' => 1,
                    'published_at' => $now->copy()->subHours($i + 1),
                    'author_id' => 1,
                ])
            );
            $post->tags()->sync($tagIds);
        }

        $arPosts = [
            [
                'slug' => 'introducing-laflux-cms-laravel-13',
                'title' => 'إطلاق نظام LaFlux CMS على لارافيل 13',
                'excerpt' => 'تعرف على كيفية دمج قوة لارافيل 13 مع سهولة إدارة المحتوى والمكتبة الإعلامية والنماذج الديناميكية.',
                'content' => '<p>يوفر نظام LaFlux CMS بنية مرنة لإدارة الصفحات والمقالات والقوائم والبانرات بسهولة تامة مع دعم كامل للغة العربية واتجاه RTL.</p>',
                'category_id' => $arTech->id,
                'featured_image' => 'themes/default/images/post-1.svg',
                'tags' => [$arTagLaravel->id, $arTagCms->id],
            ],
            [
                'slug' => 'designing-multipurpose-blade-themes',
                'title' => 'تصميم قوالب Blade احترافية متعددة الاستخدام',
                'excerpt' => 'كيف تبني قوالب سريعة ومتجاوبة مع دعم كامل للغات المتعددة والاتجاه من اليمين إلى اليسار.',
                'content' => '<p>يتيح هيكل القوالب المنفصل تخصيص التصميم بسهولة دون التأثير على نواة النظام.</p>',
                'category_id' => $arDesign->id,
                'featured_image' => 'themes/default/images/post-2.svg',
                'tags' => [$arTagCms->id],
            ],
            [
                'slug' => 'scaling-multilingual-content-workflows',
                'title' => 'إدارة المحتوى متعدد اللغات بسهولة',
                'excerpt' => 'أنشئ صفحات وقوائم ومقالات لكل لغة مع روابط تلقائية واضحة لمحركات البحث.',
                'content' => '<p>بمجرد تفعيل أكثر من لغة من لوحة التحكم، تتحول الروابط تلقائياً إلى <code>/en/</code> و <code>/ar/</code>.</p>',
                'category_id' => $arTech->id,
                'featured_image' => 'themes/default/images/post-3.svg',
                'tags' => [$arTagLaravel->id],
            ],
        ];

        foreach ($arPosts as $i => $data) {
            $tagIds = $data['tags'];
            unset($data['tags']);
            $post = Post::updateOrCreate(
                ['slug' => $data['slug'], 'language_code' => 'ar'],
                array_merge($data, [
                    'meta_title' => $data['title'],
                    'meta_keywords' => 'لارافيل, إدارة المحتوى',
                    'meta_description' => $data['excerpt'],
                    'status' => 1,
                    'published_at' => $now->copy()->subHours($i + 1),
                    'author_id' => 1,
                ])
            );
            $post->tags()->sync($tagIds);
        }

        // 8. Menus (EN & AR — Header & Footer)
        $enHeader = Menu::updateOrCreate(
            ['slug' => 'main-navigation', 'language_code' => 'en'],
            ['name' => 'Main Navigation (EN)', 'location' => 'header', 'status' => 1]
        );
        MenuItem::where('menu_id', $enHeader->id)->delete();
        MenuItem::create(['menu_id' => $enHeader->id, 'title' => 'Home', 'type' => 'custom', 'url' => '/en', 'ordering' => 1, 'status' => 1]);
        MenuItem::create(['menu_id' => $enHeader->id, 'title' => 'About Us', 'type' => 'page', 'reference_id' => $enAbout->id, 'ordering' => 2, 'status' => 1]);
        MenuItem::create(['menu_id' => $enHeader->id, 'title' => 'Services', 'type' => 'page', 'reference_id' => $enServices->id, 'ordering' => 3, 'status' => 1]);
        $enTopics = MenuItem::create(['menu_id' => $enHeader->id, 'title' => 'Categories', 'type' => 'custom', 'url' => '#', 'ordering' => 4, 'status' => 1]);
        MenuItem::create(['menu_id' => $enHeader->id, 'parent_id' => $enTopics->id, 'title' => 'Technology', 'type' => 'category', 'reference_id' => $enTech->id, 'ordering' => 1, 'status' => 1]);
        MenuItem::create(['menu_id' => $enHeader->id, 'parent_id' => $enTopics->id, 'title' => 'Design', 'type' => 'category', 'reference_id' => $enDesign->id, 'ordering' => 2, 'status' => 1]);
        MenuItem::create(['menu_id' => $enHeader->id, 'title' => 'Contact', 'type' => 'page', 'reference_id' => $enContact->id, 'ordering' => 5, 'status' => 1]);

        $enFooter = Menu::updateOrCreate(
            ['slug' => 'footer-navigation', 'language_code' => 'en'],
            ['name' => 'Footer Navigation (EN)', 'location' => 'footer', 'status' => 1]
        );
        MenuItem::where('menu_id', $enFooter->id)->delete();
        MenuItem::create(['menu_id' => $enFooter->id, 'title' => 'About Us', 'type' => 'page', 'reference_id' => $enAbout->id, 'ordering' => 1, 'status' => 1]);
        MenuItem::create(['menu_id' => $enFooter->id, 'title' => 'Services', 'type' => 'page', 'reference_id' => $enServices->id, 'ordering' => 2, 'status' => 1]);
        MenuItem::create(['menu_id' => $enFooter->id, 'title' => 'Contact', 'type' => 'page', 'reference_id' => $enContact->id, 'ordering' => 3, 'status' => 1]);

        $arHeader = Menu::updateOrCreate(
            ['slug' => 'main-navigation', 'language_code' => 'ar'],
            ['name' => 'القائمة الرئيسية (AR)', 'location' => 'header', 'status' => 1]
        );
        MenuItem::where('menu_id', $arHeader->id)->delete();
        MenuItem::create(['menu_id' => $arHeader->id, 'title' => 'الرئيسية', 'type' => 'custom', 'url' => '/ar', 'ordering' => 1, 'status' => 1]);
        MenuItem::create(['menu_id' => $arHeader->id, 'title' => 'من نحن', 'type' => 'page', 'reference_id' => $arAbout->id, 'ordering' => 2, 'status' => 1]);
        MenuItem::create(['menu_id' => $arHeader->id, 'title' => 'خدماتنا', 'type' => 'page', 'reference_id' => $arServices->id, 'ordering' => 3, 'status' => 1]);
        MenuItem::create(['menu_id' => $arHeader->id, 'title' => 'التقنية', 'type' => 'category', 'reference_id' => $arTech->id, 'ordering' => 4, 'status' => 1]);
        MenuItem::create(['menu_id' => $arHeader->id, 'title' => 'اتصل بنا', 'type' => 'page', 'reference_id' => $arContact->id, 'ordering' => 5, 'status' => 1]);

        $arFooter = Menu::updateOrCreate(
            ['slug' => 'footer-navigation', 'language_code' => 'ar'],
            ['name' => 'قائمة التذييل (AR)', 'location' => 'footer', 'status' => 1]
        );
        MenuItem::where('menu_id', $arFooter->id)->delete();
        MenuItem::create(['menu_id' => $arFooter->id, 'title' => 'من نحن', 'type' => 'page', 'reference_id' => $arAbout->id, 'ordering' => 1, 'status' => 1]);
        MenuItem::create(['menu_id' => $arFooter->id, 'title' => 'خدماتنا', 'type' => 'page', 'reference_id' => $arServices->id, 'ordering' => 2, 'status' => 1]);
        MenuItem::create(['menu_id' => $arFooter->id, 'title' => 'اتصل بنا', 'type' => 'page', 'reference_id' => $arContact->id, 'ordering' => 3, 'status' => 1]);
    }
}

