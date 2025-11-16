# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Quick Reference

### Common Commands

**Build & Development:**
- `npm install` - Install dependencies
- `npm run dev` - Watch mode for TailwindCSS (frontend + admin styles)
- `npm run build` - Production build (minified CSS only)
- `npm run build:prod` - Full production build (CSS + minified JS)
- `npm run build:js` - Minify JavaScript with Terser

**Asset Optimization:**
- `composer install` - Install optional PHP minifier (matthiasmullie/minify)
- Theme Options → Asset Optimization → Purge Minified Cache (in WordPress admin)

**Testing & Validation:**
- No automated tests defined. Manual testing required for theme functionality.

## Project Overview

**Rangin** is a high-performance WordPress theme designed for software engineers to showcase portfolios and blogs. It prioritizes performance (GTMetrix A, PageSpeed 99/100) and accessibility while providing a clean, customizable admin experience.

**Key Stats:**
- Performance: GTMetrix A (100% performance, 579ms LCP)
- Accessibility: 100/100 on PageSpeed
- Theme type: WordPress theme (no Gutenberg blocks by default)
- CSS framework: TailwindCSS v3.4.0 (utility-first)
- JavaScript: Vanilla JS (no jQuery)
- Font icons: Optimized Font Awesome subset (~2.4KB)

## Architecture & Structure

### Core Organization

```
/inc/                    → 18 modular feature files loaded in functions.php
/src/                    → TailwindCSS source files (input.css, admin CSS)
/assets/                 → Compiled CSS, JS, fonts, images
├── /css/               → TailwindCSS output + Font Awesome subset
├── /js/                → Main app logic and utilities
├── /fonts/             → Font Awesome WOFF2 subset
└── /img/               → Theme images
Template files (root)    → front-page.php, single.php, page-*.php, etc.
```

### Module Breakdown (`/inc/`)

The theme loads 18 modules in `functions.php`. Key modules:

| Module | Purpose |
|--------|---------|
| `theme-setup.php` | Registers nav menus, image sizes, theme supports |
| `enqueue-scripts.php` | Asset loading with performance optimizations (preload, defer, async) |
| `portfolio-post-type.php` | Custom post type "portfolio" with "portfolio_category" taxonomy |
| `auto-pages.php` | Creates core pages (Home, Blog, Portfolio, About, Contact) on activation |
| `customizer.php` | Theme customizer settings + getter functions with fallbacks |
| `admin-customization.php` | Custom admin panels for Theme Options |
| `helper-functions.php` | Utility functions for templates (social links, contact info, pagination) |
| `custom-widgets.php` | Custom widgets (Quick Links, Contact Info, etc.) |
| `seo-optimization.php` | Structured data (schema.org) and SEO metadata |
| `performance.php` | Query optimization, lazy loading, dynamic image attributes |
| `asset-optimization.php` | Font subsetting, CSS purging, optional minification |
| `image-optimization.php` | AVIF/WebP conversion and responsive images |
| `nav-walker.php` | Custom menu walker for enhanced navigation |
| `comment-walker.php` | Custom comment walker for threaded comments |
| `security.php` | Output escaping and sanitization |
| `demo-content.php` | Demo portfolio items |
| `widgets.php` | Widget area registration |
| `enqueue-scripts.php` | *(loaded twice, handles all asset enqueue logic)* |

### Theme Options vs. Customizer

The theme uses a **hybrid approach**:
1. **Theme Options** (primary) - New admin panels in WordPress admin (Theme Options menu)
2. **Customizer** (fallback) - Legacy support, used if Theme Options value not set

Getter functions follow this pattern:
```php
function get_contact_email() {
    $opt = get_option('contact_email', '');  // Theme options first
    if (!empty($opt)) return $opt;
    return get_theme_mod('contact_email', '');  // Customizer fallback
}
```

### Performance Optimizations

1. **CSS:**
   - TailwindCSS purges unused styles via content scanning
   - Font Awesome subset (48 icons only)
   - Inline critical CSS in `<head>`
   - Main stylesheet uses `media=print` + `onload` trick to avoid render-blocking

2. **JavaScript:**
   - Vanilla JS (no jQuery required)
   - Main script has `defer` attribute
   - jQuery/comment-reply deferred if loaded

3. **Images:**
   - Lazy loading (`loading=lazy`) on non-critical images
   - `decoding=async` on list images
   - Image sizes: `portfolio-thumb`, `blog-thumb`, `hero-image`
   - Optional srcset generation and WebP/AVIF conversion

4. **Caching:**
   - File modification time used for cache busting
   - Minified assets cached in `wp-content/uploads/rangin-cache/min/`

## Key Hooks & Extension Points

**Theme Initialization:**
- `after_setup_theme` - Register menus, image sizes, theme supports
- `after_switch_theme` - Auto-create pages, set front/posts pages

**Asset Loading:**
- `wp_enqueue_scripts` - Load frontend CSS/JS
- `script_loader_tag` - Add `defer`/`async` attributes
- `style_loader_tag` - Add media/onload for CSS

**Content Rendering:**
- `the_content` - Add lazy loading to images
- `wp_get_attachment_image_attributes` - Dynamic image sizes
- `wp_head` - Output schema.org structured data

**Admin:**
- `admin_init` - Register theme options
- `admin_menu` - Add custom admin pages
- `login_enqueue_scripts` - Custom login styling

## Custom Post Types & Taxonomies

**Portfolio Post Type:**
- Post type slug: `portfolio`
- Taxonomy: `portfolio_category` (hierarchical)
- Default categories (created on activation): Frontend, Backend, Mobile
- Custom template: `single-portfolio.php`
- REST API enabled

## Custom Widgets

Located in `inc/custom-widgets.php`:
- `Rangin_Quick_Links_Widget` - Navigation menu in footer
- `Rangin_Contact_Info_Widget` - Contact details + location
- Plus widgets for Search, Recent Posts, Categories (if enabled)

Widget areas:
- `sidebar-1` - Blog sidebar
- `footer-1` - Footer area

## Styling & CSS

**Source to Output:**
- Input: `/src/input.css` (TailwindCSS + custom styles)
- Output: `/assets/css/style.css` and `/assets/css/style.min.css`
- Watch mode: `npm run dev` (auto-rebuilds on save)

**CSS Architecture:**
1. **Base Layer** - Font stacks, HTML resets, smooth scrolling
2. **Components Layer** - Card styles, button variants, hero sections
3. **Utilities Layer** - Custom animations, grid helpers

**Dark Mode:**
- TailwindCSS: `darkMode: 'class'` (class-based)
- JavaScript toggle in `assets/js/app.js`
- Persisted in localStorage
- CSS transitions for smooth switching

**TailwindCSS Config:**
- File: `tailwind.config.js`
- Custom color palette (primary, secondary, accent)
- Breakpoints: `sm`, `md`, `lg`, `xl`, `2xl`

## Image & Icon Guidelines

**Image Sizes (registered in theme-setup.php):**
- `portfolio-thumb` - 600x400px (portfolio grid)
- `blog-thumb` - 600x400px (blog list)
- `hero-image` - 1200x600px (hero sections)

**Icons:**
- Font Awesome subset (WOFF2 format)
- Used via `<i class="fa-solid fa-icon-name"></i>` in templates
- Subset includes: home, code, briefcase, linkedin, github, twitter, etc.
- To add more icons: edit `inc/asset-optimization.php` and regenerate subset

**Image Optimization:**
- Optional AVIF/WebP conversion in Theme Options → Image Optimization
- Bulk convert tool available for existing uploads
- Lazy loading applied automatically to post/page images

## Development Workflow

### Making Style Changes

1. Edit `/src/input.css` (add TailwindCSS classes or custom CSS)
2. Run `npm run dev` to watch for changes
3. Styles automatically rebuild to `/assets/css/style.css`
4. Test in browser, verify performance impact

### Adding JavaScript

1. Edit `/assets/js/app.js` or `/assets/js/main.js`
2. For minification: `npm run build:js` (outputs `main.min.js`)
3. Update `enqueue-scripts.php` if adding new script file

### Modifying Theme Options

1. Edit `/inc/admin-customization.php` (UI) and add corresponding getter in `/inc/helper-functions.php`
2. Or use TailwindCSS utilities in templates directly
3. Rebuild if using new TailwindCSS classes

### Adding Custom Post Types or Taxonomies

1. Create new file in `/inc/` (e.g., `inc/my-custom-post.php`)
2. Require it in `functions.php`
3. Use `register_post_type()` and `register_taxonomy()` on `init` hook

## Testing & Validation

**Performance Testing:**
- Use GTMetrix (current: A grade, 579ms LCP)
- Use PageSpeed Insights (current: 99/100)
- Test on real device with 3G throttling

**SEO Testing:**
- Structured data via `seo-optimization.php` (schema.org)
- Meta tags in `header.php`
- Test with Google Rich Results Test

**Accessibility Testing:**
- Manual keyboard navigation
- Screen reader testing (NVDA/JAWS)
- Color contrast verification

**Browser Compatibility:**
- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE 11 not supported (Tailwind v3 dropped IE support)

## Common Pitfalls & Tips

1. **Font Awesome Icons:** The subset only includes ~48 icons. Adding new icons requires regenerating the subset in `inc/asset-optimization.php`.

2. **Theme Options Not Showing:** If custom options don't appear in admin, check that they're registered in `admin-customization.php` with `register_setting()`.

3. **Styles Not Applying:** Run `npm run build` to recompile TailwindCSS. Ensure new Tailwind classes are in `/src/input.css` or template files (content scanning includes templates).

4. **Portfolio Permalinks Broken:** Visit WordPress Settings → Permalinks and click "Save Changes" to flush rewrite rules after adding portfolio items.

5. **Dark Mode Not Working:** Check that `assets/js/app.js` is loaded and browser console is clear of JS errors.

6. **Performance Regression:** Check asset sizes with `npm run build --verbose`. Font Awesome subset may grow if new icons added. Consider asset optimization options in Theme Options.

## File Modification Notes

**Modified Files (tracked in git):**
- `front-page.php` - Front page template with hero, sections
- `inc/admin-customization.php` - Admin UI and theme options
- `inc/customizer.php` - Theme customizer settings
- `inc/nav-walker.php` - Custom navigation walker
- `package.json` - Build scripts defined
- `package-lock.json` - Dependencies locked
- `assets/css/fontawesome-subset.css` - Generated Font Awesome subset

When committing changes, ensure:
- No API keys or credentials in code
- CSS/JS minified for production
- Cache busting version updated if needed
- TailwindCSS purging works (no build size regression)

## Links & References

- **README.md** - Full theme documentation (features, installation, troubleshooting)
- **Theme Options Menu** - WordPress admin `Theme Options` for customization
- **TailwindCSS Docs** - https://tailwindcss.com/docs
- **WordPress Theme Handbook** - https://developer.wordpress.org/themes/
