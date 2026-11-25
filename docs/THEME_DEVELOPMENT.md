# LaFlux CMS — Theme Development Guide

This guide explains how to build custom front-end themes for **LaFlux CMS** using `theme.json`, the **Theme Customizer**, and **Custom Page Templates** — without modifying any core CMS package code.

---

## 1. Directory Structure

Every theme lives in its own folder under `resources/themes/<theme-slug>/`, with static assets in `public/themes/<theme-slug>/`:

```text
resources/themes/my-theme/
├── theme.json                     # Theme metadata, Customizer sections, and Page Templates
└── views/
    ├── layout.blade.php           # Master HTML layout (RTL/LTR aware)
    ├── partials/
    │   ├── header.blade.php       # Navigation & language switcher
    │   ├── footer.blade.php       # Footer layout
    │   └── sidebar.blade.php      # Blog sidebar (categories & tags)
    ├── home.blade.php             # Homepage view
    ├── page.blade.php             # Default CMS page view
    ├── post.blade.php             # Single blog post view
    ├── category.blade.php         # Category archive view
    ├── tag.blade.php              # Tag archive view
    └── templates/                 # Custom Page Templates (optional)
        ├── services.blade.php
        ├── contact.blade.php
        └── landing.blade.php

public/themes/my-theme/
├── theme.css
└── images/
```

Once a folder with a valid `theme.json` is placed inside `resources/themes/`, it is **automatically discovered** in **Admin → CMS → Theme Customizer**, where you can configure and activate it.

---

## 2. `theme.json` Specification

`theme.json` has three top-level parts:
1. **Metadata** (`name`, `slug`, `version`, `author`, `description`)
2. **`sections`** — Global / Homepage sections editable in **CMS → Theme Customizer** (stored per theme + per language in `cms_theme_settings`)
3. **`page_templates`** — Per-page layouts selectable on **CMS → Pages → Add / Edit**, each with its own dynamic fields (stored in `cms_pages.template` and `cms_pages.template_data`)

### Complete Example

```json
{
    "name": "My Custom Theme",
    "slug": "my-theme",
    "version": "1.0.0",
    "author": "OrhaByte Software Labs LLP",
    "description": "Custom corporate theme for LaFlux CMS.",
    "sections": {
        "hero": {
            "label": "Hero Slider Section",
            "description": "Displayed at the top of the homepage.",
            "fields": {
                "enabled": { "type": "toggle", "label": "Show Hero Section", "default": true },
                "banner_slug": { "type": "banner", "label": "Select Banner", "default": "home-hero" },
                "fallback_title": { "type": "text", "label": "Fallback Title", "default": "Welcome" }
            }
        },
        "featured_work": {
            "label": "Featured Work",
            "description": "Pulls posts from a chosen Category and Tag.",
            "fields": {
                "enabled": { "type": "toggle", "label": "Show Section", "default": true },
                "heading": { "type": "text", "label": "Heading", "default": "Our Work" },
                "category_id": { "type": "category", "label": "Source Category", "default": "" },
                "tag_id": { "type": "tag", "label": "Filter by Tag (optional)", "default": "" },
                "sort_order": { "type": "sort", "label": "Sort Order", "default": "latest" },
                "limit": { "type": "number", "label": "Number of Posts", "default": 3 }
            }
        }
    },
    "page_templates": {
        "default": {
            "label": "Default Page",
            "description": "Standard page layout.",
            "view": "Theme::page",
            "fields": {}
        },
        "services": {
            "label": "Services Showcase",
            "description": "Displays service cards from a selected category plus a bottom CTA form.",
            "view": "Theme::templates.services",
            "fields": {
                "eyebrow": { "type": "text", "label": "Eyebrow Badge", "default": "What We Offer" },
                "subtitle": { "type": "textarea", "label": "Hero Subtitle", "default": "" },
                "category_id": { "type": "category", "label": "Services Category", "default": "" },
                "tag_id": { "type": "tag", "label": "Filter by Tag (optional)", "default": "" },
                "sort_order": { "type": "sort", "label": "Sort Order", "default": "oldest" },
                "limit": { "type": "number", "label": "Number of Services", "default": 6 },
                "form_slug": { "type": "form", "label": "Bottom Contact Form", "default": "contact-us" }
            }
        }
    }
}
```

---

## 3. Supported Field Types

Both `sections` (Theme Customizer) and `page_templates` (Page Editor) support the exact same schema field types:

| Field Type | Admin Control Rendered | Stored Value |
| :--- | :--- | :--- |
| `toggle` | Checkbox (Show / Hide) | `true` / `false` |
| `text` | Single-line text input | `string` |
| `textarea` | Multi-line textarea | `string` |
| `number` | Numeric input (`1`–`30`) | `int` |
| `color` | HTML5 color picker | Hex color (e.g. `#2563eb`) |
| `category` | Dropdown of published CMS Categories | Category `id` (or `""` for all) |
| `tag` | Dropdown of published CMS Tags | Tag `id` (or `""` for any) |
| `sort` | Sort Order dropdown (`latest`, `oldest`, `title_asc`, `title_desc`, `random`) | Sort key string |
| `banner` | Dropdown of published CMS Banners | Banner `slug` |
| `form` | Dropdown of published CMS Forms | Form `slug` |
| `page` | Dropdown of published CMS Pages *(Theme Customizer)* | Page `slug` |

---

## 4. Reading Customizer Settings & Posts in Blade

Use `ExtensionsValley\Cms\Helpers\ThemeHelper` inside your theme's Blade views:

```blade
@php
    $langCode = $currentLang->code ?? 'en';

    // 1. Read a Customizer section (automatically falls back to theme.json defaults)
    $workCfg = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSection('featured_work', $langCode);

    // 2. Fetch published Posts matching that section's Category, Tag, Sort Order & Limit
    $workPosts = !empty($workCfg['enabled'])
        ? \ExtensionsValley\Cms\Helpers\ThemeHelper::getSectionPosts($workCfg, $langCode)
        : collect();
@endphp

@if(!empty($workCfg['enabled']) && $workPosts->isNotEmpty())
    <section>
        <h2>{{ $workCfg['heading'] }}</h2>
        @foreach($workPosts as $post)
            <article>
                <h3>
                    <a href="{{ \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug) }}">
                        {{ $post->title }}
                    </a>
                </h3>
                <p>{{ $post->excerpt }}</p>
            </article>
        @endforeach
    </section>
@endif
```

---

## 5. Building Custom Page Templates

1. Create a Blade file inside `resources/themes/<theme-slug>/views/templates/`, e.g. `templates/services.blade.php`.
2. Register it under `"page_templates"` in `theme.json` with `"view": "Theme::templates.services"`.
3. Inside your template Blade view, you automatically receive:
   - `$page` — The `Page` Eloquent model (`title`, `slug`, `content`, `featured_image`, `author`, `language_code`, etc.)
   - `$templateData` — Associative array of that page's saved Template Options merged with defaults from `theme.json`
   - `$currentLang`, `$activeLanguages`, `$isMultilingual`, `$headerMenu`, `$footerMenu`, `$sidebarCategories`, `$sidebarTags`

### Example Page Template (`views/templates/services.blade.php`)

```blade
@extends('Theme::layout')

@php
    $langCode = $currentLang->code ?? 'en';
    $tpl = $templateData ?? [];

    $services = \ExtensionsValley\Cms\Helpers\ThemeHelper::getSectionPosts([
        'category_id' => $tpl['category_id'] ?? '',
        'tag_id'      => $tpl['tag_id'] ?? '',
        'sort_order'  => $tpl['sort_order'] ?? 'oldest',
        'limit'       => $tpl['limit'] ?? 6,
    ], $langCode);
@endphp

@section('content')
    <h1>{{ $page->title }}</h1>
    <p>{{ $tpl['subtitle'] ?? '' }}</p>

    {{-- Render TinyMCE content with shortcode support --}}
    <div>@shortcode($page->content)</div>

    {{-- Render posts from the selected Services Category --}}
    <div class="services-grid">
        @foreach($services as $service)
            <div class="service-card">
                <h3>{{ $service->title }}</h3>
                <p>{{ $service->excerpt }}</p>
            </div>
        @endforeach
    </div>

    {{-- Render selected CMS Form --}}
    @if(!empty($tpl['form_slug']))
        {!! \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render('[form slug="' . e($tpl['form_slug']) . '"]') !!}
    @endif
@endsection
```

### Safe Theme Switching Fallback
If a page uses a template key (e.g. `services`) that does not exist in another theme you switch to later, `ThemeHelper::resolvePageTemplateView()` automatically falls back to `Theme::page` so the page always renders without errors.

---

## 6. Essential Helpers & Directives for Themes

| Helper / Directive | Usage |
| :--- | :--- |
| `CmsHelper::url('page/' . $slug)` | Generates `/page/about` in single-language mode or `/{lang}/page/about` when 2+ languages are active. |
| `@shortcode($page->content)` | Renders `[banner slug="..."]` and `[form slug="..."]` embedded inside TinyMCE content. |
| `ShortcodeHelper::render('[banner slug="..."]')` | Programmatically renders a Banner or Form shortcode in Blade. |
| `ThemeHelper::getSection('key', $lang)` | Fetches merged Customizer settings for a section. |
| `ThemeHelper::getSectionPosts($cfg, $lang)` | Queries published posts filtered by `category_id`, `tag_id`, `sort_order`, `limit`, and language. |
| `<html lang="{{ $currentLang->code }}" dir="{{ $currentLang->direction }}">` | Enables automatic LTR/RTL layout switching for multilingual sites. |

