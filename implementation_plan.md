# OnTheFly Plugin Implementation Plan

This plan details the implementation Strategy for the OnTheFly WordPress plugin to meet the technical requirements.

## Proposed Architecture & File Structure

The project will use the `OnTheFly\Core` namespace and follow an OOP, modular design. The files will be created in `m:\OnTheFly`.

- **`onthefly.php`** 
  Main WordPress plugin header file. Bootstraps the application and registers hooks.
- **`src/Plugin.php`**
  Main controller initializing translation hooks (`the_content`, `the_title`) and setting up the router and admin settings.
- **`src/TranslationEngine.php`**
  Handles `DOMDocument` logic. Intersects HTML, extracts text nodes, translates them via providers, and reforms the HTML.
- **`src/Router.php`**
  Intercepts standard WP queries to determine language switching via query parameters (e.g., `?lang=es`).
- **`src/Cache.php`**
  Wrapper around WordPress Transients API for managing the caching layer seamlessly.
- **`src/Providers/TranslationProviderInterface.php`**
  Interface for modular provider integration.
- **`src/Providers/GoogleTranslate.php`**
  Implementation of the TranslationProviderInterface for Google Translate. 
- **`src/Admin/Settings.php`**
  Generates a minimalist admin UI for configuring the API keys, target languages, and cache purging methods.

## Technical Standards Execution

- **InnoSoft Standards:** Every file except the main `onthefly.php` (which needs the WP plugin header) will be strictly free of comments.
- **Formatting:** PSR-12 compliant where possible, except using exactly 2-space indentation throughout all files as explicitly requested.
- **Namespace:** Set to `OnTheFly\Core` for all generated classes. 
- **HTML Parsing Approach:** `DOMDocument` will iteratively loop through `DOMText` nodes, keeping all attribute tags intact.

## Open Questions

1. Do you want me to write an external autoloader mechanism (like `spl_autoload_register`) or will you be using `composer` for your class loading? (I can provide a basic autoloader included in the main file out of the box).
2. For the Google Translate API, is this the official Cloud Translation API? I'll build it using `wp_remote_post` against the official endpoint.

## Verification Plan

### Automated/Code Verification
- Review files for complete adherence to 2-space indentation.
- Verify absolutely zero comments in the PHP classes (`src/` folder files).
- Validate namespace definitions.

### Manual Verification
- Review code implementation for correct WordPress actions and filters (`the_content`, `the_title`, `add_options_page`).
