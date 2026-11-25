<?php

namespace ExtensionsValley\Cms\Events;

\Event::listen('admin.menu.groups', function ($collection) {
    $collection->put('extensionsvalley.cms', [
        'menu_text' => 'CMS',
        'menu_icon' => '<i class="fa fa-newspaper-o"></i>',
        'acl_key' => 'extensionsvalley.cms.panel',
        'sub_menu' => [
            '0' => [
                'link' => url('/') . '/admin/ExtensionsValley/cms/list/cms_posts',
                'menu_text' => 'Posts',
                'acl_key' => 'extensionsvalley.cms.posts',
            ],
            '1' => [
                'link' => url('/') . '/admin/ExtensionsValley/cms/list/cms_pages',
                'menu_text' => 'Pages',
                'acl_key' => 'extensionsvalley.cms.pages',
            ],
            '2' => [
                'link' => url('/') . '/admin/ExtensionsValley/cms/list/cms_categories',
                'menu_text' => 'Categories',
                'acl_key' => 'extensionsvalley.cms.categories',
            ],
            '3' => [
                'link' => url('/') . '/admin/ExtensionsValley/cms/list/cms_tags',
                'menu_text' => 'Tags',
                'acl_key' => 'extensionsvalley.cms.tags',
            ],
            '4' => [
                'link' => url('/') . '/admin/ExtensionsValley/cms/list/cms_menus',
                'menu_text' => 'Menus',
                'acl_key' => 'extensionsvalley.cms.menus',
            ],
            '5' => [
                'link' => url('/') . '/admin/cms/media',
                'menu_text' => 'Media Library',
                'acl_key' => 'extensionsvalley.cms.media',
            ],
            '6' => [
                'link' => url('/') . '/admin/ExtensionsValley/cms/list/cms_banners',
                'menu_text' => 'Banners',
                'acl_key' => 'extensionsvalley.cms.banners',
            ],
            '7' => [
                'link' => url('/') . '/admin/ExtensionsValley/cms/list/cms_forms',
                'menu_text' => 'Forms',
                'acl_key' => 'extensionsvalley.cms.forms',
            ],
            '8' => [
                'link' => url('/') . '/admin/ExtensionsValley/cms/list/cms_languages',
                'menu_text' => 'Languages',
                'acl_key' => 'extensionsvalley.cms.languages',
            ],
            '9' => [
                'link' => url('/') . '/admin/cms/customizer',
                'menu_text' => 'Theme Customizer',
                'acl_key' => 'extensionsvalley.cms.customizer',
            ],
        ],
    ]);
});

