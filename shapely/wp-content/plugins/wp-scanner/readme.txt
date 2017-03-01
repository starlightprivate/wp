=== WP Scanner - Performance and Security ===
Contributors: A5hleyRich
Tags: security, performance, scan, scanning, scanner, monitor, monitoring, load, load time, permissions, secure, cache, cdn
Requires at least: 3.5
Tested up to: 4.5.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Scan your WordPress site and receive recommendations on how to improve load time, performance and security.

== Description ==

Monitor your site's load time, performance and security using [WP Scanner](https://wpscanner.io). Gain an insight into how quickly your site loads for visitors over time and receive suggestions on how to improve performance. Ensure your site is secure by monitoring file changes, permissions, server headers and other security concerns.

= Metrics =

View important metrics about your WordPress install, including:

* Load time
* WordPress version
* PHP version
* Plugin updates
* Content breakdown

= Performance =

The following performance rules are checked when scanning your site:

* Use PHP 7 or HHVM
* Enable object caching
* Minimize HTTP Requests
* Use a Content Delivery Network
* Avoid empty src or href
* Add an Expires or a Cache-Control Header
* Gzip Components
* Put StyleSheets at the Top
* Put Scripts at the Bottom
* Avoid CSS Expressions
* Make JavaScript and CSS External
* Reduce DNS Lookups
* Minify JavaScript and CSS
* Avoid Redirects
* Remove Duplicate Scripts
* Configure ETags
* Make AJAX Cacheable
* Use GET for AJAX Requests
* Reduce the Number of DOM Elements
* No 404s
* Reduce Cookie Size
* Use Cookie-Free Domains for Components
* Avoid Filters
* Do Not Scale Images in HTML
* Make favicon.ico Small and Cacheable

= Security =

The following security rules are checked when scanning your site:

* Verify WordPress Core Files
* Verify Directory and File Permissions
* Serve Site Over HTTPS
* Keep Plugins Updated
* Keep WordPress Updated
* Keep PHP Updated
* Disable Debug Display
* Disable File Editing
* Remove Accounts with "Admin" Username
* Change the Default Table Prefix
* Configure Public-Key-Pins Header
* Configure Content Security Policy Header
* Configure X-Frame-Options Header
* Configure X-Content-Type-Options Header
* Configure X-Xss-Protection Header
* Configure Strict-Transport-Security Header
* Disable Server Header
* Disable X-Powered-By Header

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-scanner` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the WP Scanner through the 'Plugins' screen in WordPress.
1. Create a free account at [https://wpscanner.io](https://wpscanner.io) to generate your API keys.
1. Visit the Settings -> WP Scanner screen and enter your API keys.

== Frequently Asked Questions ==

= Why do I need to create an account at wpscanner.io? =

Scanning a site for performance and security issues is a resource intensive process, which can take time to complete. With a **free** [WP Scanner](https://wpscanner.io) account we offload the scanning process to our own servers, so as not to impact site performance. Doing so also allows us to detect additional performance and security issues, which we couldn't from the plugin alone.

= Is WP Scanner free to use? =

Yes, WP Scanner is completely free to use for 1 site. If you need to scan additional sites, premium [plans](https://wpscanner.io/#pricing) are available.

= Is WP Scanner secure? =

Absolutely! No sensitive data is sent to our servers.

= Will this plugin impact the performance of my website? =

No, it will not.

== Screenshots ==

1. Dashboard showing latest scan results for each site
2. Metrics showing load time, versions and content breakdown
3. Performance scores with recommendations on how to improve them
4. Security scores with recommendations on how to improve them
5. Headers returned when scanning the site

== Changelog ==

= WP Scanner 1.0.2 =
* Bug fix: Unable to activate sites.

= WP Scanner 1.0.1 =
* Bug fix: Core files incorrectly showing as modified when using non-standard locale.

= WP Scanner 1.0 =
* Initial plugin release.

== Upgrade Notice ==

