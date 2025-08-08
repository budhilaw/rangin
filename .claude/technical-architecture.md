# Technical Architecture

## WordPress Theme Architecture

### File Organization Strategy
The theme follows a modular architecture with clear separation of concerns:

```
functions.php (Main Loader)
    ├── inc/theme-setup.php        → Theme support, menus, post thumbnails
    ├── inc/enqueue-scripts.php    → CSS/JS loading with proper dependencies
    ├── inc/seo.php               → Meta tags, structured data
    ├── inc/performance.php       → Caching, optimization
    ├── inc/nav-walker.php        → Custom navigation HTML structure
    ├── inc/helper-functions.php  → Utility functions
    ├── inc/custom-widgets.php    → Custom sidebar widgets
    ├── inc/customizer.php        → Theme customization options
    ├── inc/widgets.php           → Widget area registration
    ├── inc/comment-walker.php    → Comment display customization
    └── inc/admin-customization.php → Backend modifications
```

### Template Hierarchy Implementation

**Homepage Strategy**:
- `front-page.php` → Static homepage with customizable sections
- `home.php` → Blog posts index (when static front page is set)
- `index.php` → Blog archive fallback

**Content Templates**:
- `single.php` → Individual blog posts with lazy-loaded comments
- `archive.php` → Category/tag archives
- `page.php` → Static pages

### CSS Architecture

**Build Process**:
```
src/input.css (Source)
    ├── @tailwind base
    ├── @tailwind components  
    ├── @tailwind utilities
    ├── Custom components (@apply directives)
    ├── WordPress block styles
    ├── Widget styles
    └── Comment system styles
            ↓ (TailwindCSS CLI)
assets/css/style.css (Compiled & Minified)
```

**Design System**:
```scss
// Color Palette (Pastel Theme)
primary: { 50-950 }     // Blue-based primary
secondary: { 50-950 }   // Purple-based secondary  
accent: { 50-950 }      // Pink-based accent
rose: { 50-950 }        // Rose complementary
neutral: { 25, 50-950 } // Extended grays

// Components
.card → Reusable container with shadow/border
.btn → Button variants (primary, secondary, outline)
.form-group → Form field containers
```

### JavaScript Architecture

**Main Script Structure** (`assets/js/main.js`):
```javascript
(function($) {
    // Initialization
    $(document).ready(function() {
        initNavigation();        // Mobile menu, scroll effects
        initScrollEffects();     // Intersection Observer animations
        initAnimations();        // Hero section animations  
        initSkillBars();        // Progress bars (if any)
        initThemeToggle();      // Dark/light mode switching
        initLazyComments();     // Comment lazy loading
    });

    // Core Functions
    - Navigation: Mobile menu, scroll background changes
    - Theme Toggle: localStorage + system preference detection
    - Scroll Animations: Intersection Observer for performance
    - Lazy Comments: Performance optimization for comment loading
})(jQuery);
```

### Database & Customizer Integration

**Customizer Structure**:
```php
// Front Page Sections
'front_page_hero' → Hero section settings
'front_page_about' → About me customization
'front_page_contact' → Contact information  
'front_page_blog' → Featured posts selection

// Settings Organization
get_theme_mod('setting_name', 'default_value')
↓
Helper functions: get_hero_greeting(), get_about_photo(), etc.
↓
Template integration with sanitization
```

**Featured Posts Logic**:
```php
get_featured_posts() {
    // Custom post IDs (1-3)
    if (any_custom_ids_provided) {
        return selected_posts_in_order;
    } else {
        return wp_get_recent_posts(3); // Fallback
    }
}
```

### Performance Optimizations

**CSS Optimization**:
- TailwindCSS purging removes unused classes
- Minification in production build
- Critical CSS inlined for above-the-fold content

**JavaScript Optimization**:
- jQuery dependency management
- Event delegation for dynamic content
- Intersection Observer for scroll animations
- Lazy loading for non-critical content (comments)

**WordPress Optimizations**:
- Selective script/style loading
- Admin bar removal on frontend
- Image optimization hooks
- Caching-friendly architecture

### Comment System Architecture

**Lazy Loading Implementation**:
```javascript
// Intersection Observer watches comment trigger
when (comment_section_near_viewport) {
    fadeOut(loading_placeholder);
    loadComments(); // Show actual WordPress comments
    initScrollEffectsForComments();
    handleDirectCommentLinks(); // For email notifications
}
```

**Custom Comment Walker**:
- Overrides WordPress default comment HTML
- Adds modern styling classes
- Includes author badges
- Proper threading with visual hierarchy
- Moderation state indicators

### Build & Development Workflow

**Development Process**:
```bash
# TailwindCSS Development
npm run dev    # Watch mode with source maps
npm run build  # Minified production build

# File Watching
src/input.css → (changes) → npm run build → assets/css/style.css
```

**Git Workflow**:
- `.gitignore` excludes compiled assets
- Theme files tracked in version control
- Node modules and build artifacts ignored

### Integration Points

**WordPress Core Integration**:
- Theme supports: post-thumbnails, menus, widgets, html5
- Navigation: wp_nav_menu with custom walkers
- Comments: wp_list_comments with custom walker
- Widgets: register_sidebar with custom widgets

**Third-party Integration**:
- Font Awesome CDN for icons
- Google Fonts (if used)
- Social media links (configurable)

### Security Considerations

**Input Sanitization**:
```php
// Customizer settings
sanitize_text_field(), esc_url(), absint()

// Output escaping  
esc_html(), esc_url(), esc_attr()
```

**WordPress Standards**:
- Proper nonce handling in forms
- Capability checks for admin functions
- SQL injection prevention (using WP functions)
- XSS prevention through escaping

## Deployment Architecture

**Docker Environment**:
```yaml
services:
  wordpress:
    - php-custom.ini volume mount
    - 10MB upload limit
    - 512MB memory limit
    
  mysql:
    - Persistent data volume
    - UTF8 charset
```

**File Structure for Production**:
- Compiled CSS/JS assets
- Optimized images
- Minified code
- Proper WordPress file permissions
