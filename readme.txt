=== Custom Favicon ===
Contributors: hchouhan, themeist
Donate link: https://themeist.com/plugins/wordpress/custom-favicon/
Tags: favicon, icon, site icon, svg icon, dark mode
Requires at least: 6.0
Tested up to: 6.8
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPL-3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Easily add a custom favicon and Apple touch icon to your WordPress site, including support for dark mode, SVG icons, and admin dashboard branding.

== Description ==

**Custom Favicon** lets you upload and manage favicons for your WordPress website, admin area, and login screen using the native media uploader. Unlike the built-in Site Icon feature, this plugin gives you full control — including support for separate frontend and backend icons, Apple touch icons, dark mode icons, and SVG format.

**Features include:**
- Upload custom favicon for frontend (browser tab icon)
- Upload separate favicon for WordPress Dashboard and login page
- Upload Apple touch icons for iOS devices
- Upload dark mode specific favicon
- SVG favicon support
- Option to disable default WordPress Site Icon output
- Clean and simple settings page under **Settings → Custom Favicon**

This plugin is useful for:
- Replacing the default WordPress favicon
- Branding the WordPress dashboard for clients
- Adding modern favicon features with minimal setup

Official plugin page: [Custom Favicon on Themeist](https://themeist.com/plugins/wordpress/custom-favicon/)

Need help? Ask in the [Support Forum on WordPress.org](https://wordpress.org/support/plugin/custom-favicon/)

== Installation ==

1. Upload the `/custom-favicon/` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Go to **Settings → Custom Favicon** and upload your icons

== Frequently Asked Questions ==

= Will this override the default WordPress Site Icon? =
Yes. When enabled, the plugin replaces WordPress’s default favicon and Apple icon output.

= Can I use SVG files? =
Yes. Modern browsers support SVG favicons. If your theme doesn’t allow SVG uploads, you may need to enable that manually.

= Does this support light/dark mode favicons? =
Yes. You can upload separate favicons for light and dark appearance modes using the `prefers-color-scheme` media query.

= Can editors access the settings page? =
By default, only Administrators (with the `manage_options` capability) can access the settings page.
Developers can override this using the `custom_favicon_capability` filter. For example, to allow Editors:
```php
add_filter( 'custom_favicon_capability', function () {
    return 'edit_theme_options';
} );

= Where can I get help? =
You can ask your question in the [WordPress.org Support Forum](https://wordpress.org/support/plugin/custom-favicon/)

== Changelog ==

= 1.1.0 - (21 May 2025) =
* Completely refactored plugin codebase to follow WordPress coding standards (PHPCS)
* Added support for dark mode favicons using `prefers-color-scheme`
* Added support for SVG icons
* Added `apple-touch-icon` and `msapplication-TileImage` meta output
* Added option to disable WordPress’s default Site Icon output
* Improved admin settings UI and image upload experience
* Added automatic migration of old plugin settings
* Improved plugin security and sanitization
* Removed legacy Apple icon style option
* Added developer filter custom_favicon_capability to allow overriding who can access the settings page

= 1.0.3 =
* Updated readme

= 1.0.2 =
* Fixed incorrect JS path
* Upload issues resolved

= 1.0.1 =
* Replaced WP_PLUGIN_URL with plugins_url()

= 1.0.0 =
* Initial release
