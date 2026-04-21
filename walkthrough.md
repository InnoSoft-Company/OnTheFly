# OnTheFly Plugin - Implementation Walkthrough

The PHP structure for the **OnTheFly** plugin has been successfully scaffolded inside `m:\OnTheFly` exactly according to the strict requirements provided.

## Directory Structure Created

The following architecture was created adhering strictly to exactly 2-space indentation and absolute zero internal comments:

1. **`m:\OnTheFly\onthefly.php`**
   - The main entry point featuring the required WordPress plugin header and an `spl_autoload_register` autoloader to dynamically map `OnTheFly\Core` namespaces to the `src/` directory files.
2. **`m:\OnTheFly\src\Plugin.php`**
   - The primary controller. Connects the application logic, registering the router hooks, settings hooks, and the `the_content` / `the_title` filters.
3. **`m:\OnTheFly\src\TranslationEngine.php`**
   - Implements the strict `DOMDocument` logic to iterate over `DOMText` nodes securely without modifying any HTML structures or attributes (`script` & `style` nodes are securely avoided using XPath).
4. **`m:\OnTheFly\src\Router.php`**
   - Contains URL-parameter interception specifically checking for `?lang=` parameters. Uses a RegEx validation rule ensuring acceptable formats like `?lang=es` or `?lang=es-ES`.
5. **`m:\OnTheFly\src\Cache.php`**
   - A wrapper module managing standard WordPress Transients. It caches translation blocks separately by MD5 hash of text/lang and handles full cache purges.
6. **`m:\OnTheFly\src\Providers\TranslationProviderInterface.php`**
   - An interface ensuring any initialized REST provider responds correctly strictly via a `translate(array $texts, string $targetLanguage)` method footprint.
7. **`m:\OnTheFly\src\Providers\GoogleTranslate.php`**
   - Implements Google Cloud Translation API via a singular bulk `$body['q']` packet payload parsed over the `wp_remote_post` core WP function.
8. **`m:\OnTheFly\src\Admin\Settings.php`**
   - Clean UI registering standard Settings API variables (`onthefly_google_api_key`, `onthefly_target_languages`) under `Settings -> OnTheFly`. Allows quick transient flushing directly via a customized hidden POST form.

## Verification
- Code follows PSR-12 basic structure conventions adapted strictly to the `2 spaces` rule. 
- Fully respects the **no comments** policy throughout `src/*` PHP files.
- The modularity requirement has been established, allowing you to quickly add other Providers (e.g. DeepL) implementing the same Interface wrapper.
