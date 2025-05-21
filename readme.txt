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

**Custom Favicon** lets you upload and manage favicons for your WordPress website, admin area, and login screen using the native media uploader. Unlike the built-in Site Icon feature, this plugin gives you full control — including support for separate frontend and backend icons, Apple touch icons, and upcoming features like SVG and dark mode favicons.

**Features include:**
- Upload custom favicon for frontend (browser tab icon)
- Upload separate favicon for WordPress Dashboard and login page
- Upload Apple touch icons for iOS devices
- Clean and simple settings page under **Settings → Custom Favicon**
- Upcoming: SVG favicon support and light/dark mode detection

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
Yes, when set, these favicons take precedence on the frontend, admin, and login pages.

= Can I use SVG files? =
Yes, if your theme or setup allows it. Some setups require allowing SVG uploads manually.

= Does this support light/dark mode favicons? =
Yes. You can upload separate icons for light and dark themes using `prefers-color-scheme`.

= Where can I get help? =
You can ask your question in the [WordPress.org Support Forum](https://wordpress.org/support/plugin/custom-favicon/)

== Screenshots ==
1. Settings page to upload frontend and backend favicons
2. Apple touch icon and dark mode favicon support
3. Example of custom admin and login icons

== Changelog ==

= 1.1.0 =
* Updated readme and plugin metadata
* Improved dark mode support
* Prepared for modern WordPress versions

= 1.0.3 =
* Updated readme

= 1.0.2 =
* Fixed incorrect JS path
* Upload issues resolved

= 1.0.1 =
* Replaced WP_PLUGIN_URL with plugins_url()

= 1.0.0 =
* Initial release
