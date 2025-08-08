# Quick Start Guide for AI Agents

## 🚀 Project Context
This is a **WordPress theme development project** for a Software Engineer's personal website. The theme is called "Personal Website" by Ericsson Budhilaw, focusing on modern design, performance, and customization.

## 📁 Key File Locations

### Core Theme Files
```
public/wp-content/themes/ebtw/
├── functions.php           # Main loader (don't edit directly)
├── style.css              # Theme metadata
├── front-page.php          # Homepage template
├── single.php             # Blog post template  
├── comments.php           # Comment system
└── inc/                   # All functionality is here!
```

### Development Files
```
public/wp-content/themes/ebtw/
├── src/input.css          # TailwindCSS source (edit this)
├── tailwind.config.js     # TailwindCSS config
├── package.json           # NPM scripts
└── assets/css/style.css   # Compiled CSS (auto-generated)
```

## 🛠 Essential Commands

### Navigate to Theme Directory
```bash
cd /Users/budhilaw/Dev/Personal/PHP/personal-website/public/wp-content/themes/ebtw
```

### CSS Development
```bash
# Install dependencies (if needed)
npm install

# Development build (watch mode)
npm run dev

# Production build (minified)
npm run build
```

### Docker Environment
```bash
# From project root
cd /Users/budhilaw/Dev/Personal/PHP/personal-website
docker-compose up -d
```

## 🎯 Current Status

### ✅ Recently Completed
- **Comment System**: Modern, lazy-loaded, threaded comments
- **Customizer Options**: Extensive front-page customization
- **Custom Widgets**: Search, Recent Posts, Categories
- **Navigation System**: Responsive, WordPress-integrated
- **Dark Mode**: Complete theme support
- **Performance**: Lazy loading, optimized CSS

### 🔧 Architecture Overview
- **Modular PHP**: All functions split into `inc/` files
- **TailwindCSS**: Custom pastel color palette
- **JavaScript**: jQuery + Vanilla JS for performance
- **WordPress Integration**: Proper hooks, filters, and standards

## 🎨 Design System

### Colors (TailwindCSS Extended)
```javascript
// Primary: Blue-based
// Secondary: Purple-based  
// Accent: Pink-based
// Rose: Rose complementary
// Neutral: Extended grays (25, 50-950)
```

### Key CSS Classes
- `.card` - Reusable container component
- `.btn`, `.btn-primary`, `.btn-outline` - Button variants
- `.animate-on-scroll` - Intersection Observer animations
- `.theme-toggle` - Dark/light mode switcher

## 📝 Making Changes

### 1. For Styling Changes
```bash
# Edit TailwindCSS source
vim src/input.css

# Rebuild CSS  
npm run build
```

### 2. For PHP Functionality
```bash
# Edit specific feature files
vim inc/[feature].php

# No build step needed - PHP is interpreted
```

### 3. For JavaScript
```bash
# Edit main script
vim assets/js/main.js

# No build step needed - already minified/optimized
```

## 🔍 Key Functions & Helpers

### Customizer Helper Functions (inc/customizer.php)
```php
get_hero_greeting()         // Hero section greeting
get_about_me_photo()        // About section photo
get_social_linkedin()       // Social media links
get_featured_posts()        // Homepage featured posts
```

### Theme Functions (inc/theme-setup.php)
```php
personal_website_setup()    // Theme support features
register_nav_menus()        // Navigation menus
add_image_size()           // Custom image sizes
```

### Performance Functions (inc/performance.php)
```php
reading_time()             // Calculate blog post reading time
optimize_images()          // Image optimization hooks
```

## 🐛 Common Issues & Solutions

### CSS Not Updating
```bash
# Rebuild TailwindCSS
cd wp-content/themes/ebtw
npm run build
```

### PHP Errors
```bash
# Check error logs
tail -f /var/log/php_errors.log

# Or check WordPress debug.log
```

### JavaScript Issues
```bash
# Check browser console for errors
# Verify jQuery is loaded
```

## 🎯 User's Preferences (IMPORTANT!)

### Code Style
- **Modular PHP**: Keep functions.php clean, use inc/ files
- **TailwindCSS**: Use @apply directives, avoid inline styles
- **Performance First**: Always consider loading speed
- **WordPress Standards**: Follow WP coding conventions

### Design Preferences
- **Pastel Colors**: Not too techy, smooth and professional
- **Modern UI**: Card-based, clean, minimalist
- **Dark Mode**: Always maintain dark/light compatibility
- **Mobile First**: Responsive design is crucial

### Specific Requirements
- **No Contact Forms**: Manual contact only (email/social)
- **Font Awesome**: Use for all icons
- **Performance**: Lazy loading, optimized assets
- **SEO**: Perfect SEO implementation required

## ⚡ Quick Commands for Common Tasks

### Add New Customizer Setting
1. Edit `inc/customizer.php`
2. Add setting + control + helper function
3. Use in template with `get_theme_mod()`

### Create New Widget
1. Edit `inc/custom-widgets.php`  
2. Extend `WP_Widget` class
3. Register in `inc/widgets.php`

### Add New CSS Component
1. Edit `src/input.css`
2. Use `@apply` directive
3. Run `npm run build`

### Modify Navigation
1. Edit `inc/nav-walker.php` for HTML structure
2. Edit `src/input.css` for styling
3. Rebuild CSS

## 📋 Testing Checklist
- [ ] Dark/light mode toggle works
- [ ] Mobile responsive design
- [ ] Comment system loads properly
- [ ] Customizer options save/display correctly
- [ ] CSS builds without errors
- [ ] No PHP errors in logs

## 💡 Pro Tips
1. **Always test in both themes** (dark/light)
2. **Use browser dev tools** for responsive testing
3. **Check WordPress admin** for customizer options
4. **Monitor performance** with lazy loading
5. **Follow the existing patterns** for consistency

This should get you up to speed quickly! Check the other `.claude/` files for detailed architecture and next steps.
