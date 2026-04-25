<?php

namespace OnTheFly\Core\Admin;

if (!defined('ABSPATH')) {
    exit;
}

use OnTheFly\Core\Cache;

class Settings
{
    public function registerHooks(): void
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addAdminMenu(): void
    {
        add_options_page(
            'OnTheFly Settings',
            'OnTheFly',
            'manage_options',
            'onthefly',
            [$this, 'renderSettingsPage']
        );
    }

    public function registerSettings(): void
    {
        register_setting('onthefly_settings_group', 'onthefly_active_provider', [
            'sanitize_callback' => 'sanitize_text_field'
        ]);
        register_setting('onthefly_settings_group', 'onthefly_google_api_key', [
            'sanitize_callback' => 'sanitize_text_field'
        ]);
        register_setting('onthefly_settings_group', 'onthefly_deepl_api_key', [
            'sanitize_callback' => 'sanitize_text_field'
        ]);
        register_setting('onthefly_settings_group', 'onthefly_target_languages', [
            'sanitize_callback' => 'sanitize_text_field'
        ]);
    }

    public function renderSettingsPage(): void
    {
        if (isset($_POST['onthefly_clear_cache']) &&
            check_admin_referer('onthefly_clear_cache_action', 'onthefly_clear_cache_nonce')
        ) {
            $cache = new Cache();
            $cache->clearAll();
            echo '<div class="notice notice-success is-dismissible"><p>Cache cleared successfully.</p></div>';
        }

        $activeProvider = get_option('onthefly_active_provider', 'google');

        ?>
        <div class="wrap">
            <h1>OnTheFly Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('onthefly_settings_group'); ?>
                <?php do_settings_sections('onthefly_settings_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Active Translation Provider</th>
                        <td>
                            <select name="onthefly_active_provider">
                                <option value="google" <?php selected($activeProvider, 'google'); ?>>
                                    Google Translate
                                </option>
                                <option value="deepl" <?php selected($activeProvider, 'deepl'); ?>>DeepL</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Google Translate API Key</th>
                        <td>
                            <input type="password"
                                   name="onthefly_google_api_key"
                                   value="<?php echo esc_attr(get_option('onthefly_google_api_key')); ?>"
                                   class="regular-text" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">DeepL API Key</th>
                        <td>
                            <input type="password"
                                   name="onthefly_deepl_api_key"
                                   value="<?php echo esc_attr(get_option('onthefly_deepl_api_key')); ?>"
                                   class="regular-text" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Target Languages (comma separated)</th>
                        <td>
                            <input type="text"
                                   name="onthefly_target_languages"
                                   value="<?php echo esc_attr(get_option('onthefly_target_languages')); ?>"
                                   class="regular-text" />
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>

            <hr />

            <h2>Cache Management</h2>
            <form method="post" action="">
                <?php wp_nonce_field('onthefly_clear_cache_action', 'onthefly_clear_cache_nonce'); ?>
                <input type="hidden" name="onthefly_clear_cache" value="1" />
                <?php submit_button('Clear Translation Cache', 'secondary'); ?>
            </form>
        </div>
        <?php
    }
}
