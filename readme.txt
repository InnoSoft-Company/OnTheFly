=== OnTheFly ===
Contributors: innosoft
Tags: translation, automatic translation, google translate, deepl, server-side translation, seo translation
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

OnTheFly is a professional, high-performance, server-side content translation plugin for WordPress. It acts autonomously by instantly intercepting queries and translating the HTML payload before rendering.

== Description ==

**OnTheFly** is a professional, high-performance, server-side content translation plugin for WordPress. It acts autonomously by instantly intercepting queries and translating the HTML payload before rendering, utilizing leading cloud providers (Google Translate & DeepL) via a clean, structure-preserving DOM Parser.

Unlike client-side translation solutions, OnTheFly operates entirely on the server — delivering translated pages that are fully SEO-indexable, blazing fast for the end user, and completely invisible in operation.

= Key Features =

* **DOM Parsing without Structural Loss:** Utilizes `DOMDocument` and `DOMXPath` to isolate and strictly intercept text nodes (`DOMText`). It entirely avoids `script` and `style` blocks while keeping essential attributes (`href`, classes) unharmed.
* **Multiple Translation Providers:** Instantly toggle between **Google Cloud Translation API** and **DeepL API** securely from the admin dashboard.
* **Batched API Processing:** Automatically chunks enormous string payloads into robust 50-limit blocks to completely eliminate `HTTP 413 Payload Too Large` restrictions.
* **Intelligent Transient Caching:** MD5-hashed query storing mechanism to save your API costs and deliver responses instantly (On the fly) on repeated requests.
* **100% SEO Optimized:** Dynamically modifies the global WordPress `<html lang="xx">` tags and `<title>` headers based on your translation route to ensure seamless indexability across search engines.
* **Zero-Bloat Architecture:** Compliant with custom strict styling and a PSR-4 namespace (`OnTheFly\Core`) decoupled autoloader.

== Installation ==

1. Download the latest version of the plugin as a `.zip` file.
2. Log into your WordPress admin panel.
3. Browse to `Plugins` -> `Add New` -> `Upload Plugin`.
4. Select the `.zip` file and click **Install Now**.
5. Once imported, click **Activate Plugin**.

== Screenshots ==

1. The OnTheFly Settings page in the WordPress dashboard.
2. Example of a page translated on-the-fly using the `?lang=es` parameter.

== Frequently Asked Questions ==

= Does OnTheFly work with page builders? =
Yes. OnTheFly hooks into `the_content` and `the_title` WordPress filters which fire after page builders render their output.

= Will it translate my theme's navigation menus? =
Currently, OnTheFly translates content passed through `the_content` and `the_title` filters. Theme elements outside these hooks (menus, widgets) are not translated in this version.

= How much does it cost? =
The plugin is free, but you will need an API key from Google Cloud or DeepL. Both providers offer free tiers (usually up to 500,000 characters per month).

== Changelog ==

= 1.0.0 =
* Initial release.
* Support for Google Translate and DeepL.
* Transient-based caching system.
* DOM-based HTML parsing.
