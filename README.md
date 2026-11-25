# LaFlux CMS

**Powered By OrhaByte Software Labs LLP**

A modern, modular, WordPress-inspired **Laravel 13.x Content Management System** built on a clean package architecture (`ExtensionsValley/Dashboard` & `ExtensionsValley/Cms`). Designed for developers and teams who want a fast, extensible Blade-based CMS with built-in role-based access control (ACL), multilingual front-end routing, shortcodes, media library, banners, and dynamic forms — with zero bloated API boilerplate.

---

## Tech Stack & Requirements

- **Framework:** Laravel **13.x**
- **PHP:** **8.3+** (PHP 8.3, 8.4, or 8.5)
- **Database:** MySQL / MariaDB (SQLite supported for automated tests)
- **Packages:** Yajra DataTables v13, Laravel Sanctum v4, TinyMCE 6 (CDN), Select2

---

## Key Features

### Content Management (`ExtensionsValley/Cms`)
- **Posts & Pages:** Full CRUD with **TinyMCE 6 WYSIWYG** editor, live clickable permalinks, **Featured Images** (upload or pick from Media Library), **Scheduled Publishing** (`published_at`), and complete **SEO Meta** fields (Meta Title, Meta Keywords, Meta Description).
- **Categories & Tags:** Nested parent/child categories, post tags (multi-select), and relation-safe trash/unpublish checks.
- **WordPress-Style Media Library:** Unified uploads under `public/uploads/cms/YYYY/MM/` with drag-and-drop multi-file upload, search & type filters, attachment details modal (title, alt text, one-click URL copy), and a reusable **Media Picker** modal integrated into Featured Images, Banner Slides, and TinyMCE content insertion.
- **Dynamic Navigation Menus:** Manage `header`, `footer`, and `sidebar` menus linking to Pages, Posts, Categories, or Custom URLs with nested sub-menus and **drag-and-drop reordering**.
- **Banners & Sliders:** Create multi-slide carousels with slide images, titles, descriptions, Read More links, and drag-and-drop slide ordering. Embed anywhere using `[banner slug="..."]`.
- **Dynamic Form Builder & Submissions:** Build custom forms with configurable fields (`text`, `email`, `textarea`, `number`, `tel`, `date`, `select`, `radio`, `checkbox`), required rules, drag-and-drop field ordering, automatic server-side validation, and a built-in **Submissions Inbox**. Embed anywhere using `[form slug="..."]`.
- **Shortcode Engine:** Embed `[banner slug="..."]` and `[form slug="..."]` inside any Page or Post content, or render them directly in Blade templates with `@shortcode('[banner slug="home-banner"]')`.
- **Multilingual Front-End Support:** Manage languages in **CMS → Languages** (2-letter ISO codes + `LTR`/`RTL` text direction).
  - **Single active language:** Clean URLs without a language prefix (`/cms`, `/page/about-us`, `/post/...`).
  - **Multiple active languages:** Automatic 2-letter language prefixes (`/en/page/about-us`, `/ar/page/about-us`), language switcher in the header, and RTL support — while keeping the admin panel clean and single-language.

### Admin Core & Platform (`ExtensionsValley/Dashboard`)
- **Modern LaFlux Auth UI:** Sleek login and password reset screens with a dedicated `admin` authentication guard (independent from front-end sessions).
- **Dynamic Dashboard:** Live overview tiles (Users, Posts, Pages, Categories, Tags, Menus), Quick Actions, Recent Posts, Posts by Category, Recent Pages, and Recent Users.
- **Role-Based Access Control (ACL):** Fine-grained View, Add, Edit, and Trash permissions per User Group.
- **Users & Groups Management:** Full user, profile, and role management.
- **General Settings & URL Migration Tool:** Manage site name, logo, favicon, copyright, default SEO meta, plus a built-in **Find & Replace URLs** tool (with Dry Run preview) for effortless local-to-production migrations.
- **Modular Extension Manager:** Package-based architecture allowing additional modules to be registered, activated, or deactivated cleanly.

---

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url> laflux-cms
   cd laflux-cms
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Configure environment:**
   Copy `.env.example` to `.env`, set your `APP_URL` and database credentials, and generate the application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations & seeders:**
   ```bash
   php artisan migrate
   php artisan db:seed
   composer dump-autoload -o
   ```

5. **(Optional) One-Click Demo Content Import:**
   Populate the site immediately with multilingual demo data (English LTR + Arabic RTL languages, 3-slide Hero Banner, About/Services/Contact pages, 6 sample posts with categories & tags, Header/Footer menus, and a Contact Us form):
   ```bash
   php artisan laflux:import-demo
   ```

6. **Set folder permissions:**
   Ensure your web server user has write permissions on the storage, cache, and upload directories:
   ```bash
   chmod -R 775 storage bootstrap/cache public/uploads/cms public/packages/extensionsvalley/dashboard/images
   ```

---

## Default Admin Login

- **Admin URL:** `https://yourdomain.com/admin` (or `http://localhost/laravel-admin/public/admin`)
- **Email:** `admin@wwm.com`
- **Password:** `123456`

> After signing in for the first time, you can customize role permissions from the top-right profile menu → **Access Permission**.

---

## Front-End Theme, Routes & Shortcodes

The default multi-purpose Blade theme lives in `resources/themes/default/` (with assets in `public/themes/default/`), keeping front-end layouts cleanly separated from core CMS packages.

| Route (Single Language) | Route (Multilingual) | Description |
| :--- | :--- | :--- |
| `/` | `/{lang}` | Main Front-End Homepage (Hero Slider, Highlights, Latest Posts, Contact Form) |
| `/cms` | `/{lang}/cms` | Blog Home & Latest Posts |
| `/page/{slug}` | `/{lang}/page/{slug}` | Single CMS Page |
| `/post/{slug}` | `/{lang}/post/{slug}` | Single Blog Post |
| `/category/{slug}` | `/{lang}/category/{slug}` | Category Archive |
| `/tag/{slug}` | `/{lang}/tag/{slug}` | Tag Archive |

**Shortcode Examples (Pages, Posts, or Blade via `@shortcode(...)`):**
```text
[banner slug="home-hero"]
[form slug="contact-us"]
```

---

## Running Tests

```bash
php artisan test
```

---

## Author & Credits

- **Author:** [Jobin Jose](https://github.com/Jobinjose01)
- **Powered By:** **OrhaByte Software Labs LLP**

## License

This project is open-sourced software licensed under the [MIT License](LICENSE.md).
