<?php

namespace OnTheFly\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Cache
{
    private $prefix = 'onthefly_';
    private $expiration = 30 * DAY_IN_SECONDS;

    public function get(string $key)
    {
        return get_transient($this->prefix . $key);
    }

    public function set(string $key, $value): bool
    {
        return set_transient($this->prefix . $key, $value, $this->expiration);
    }

    public function clearAll(): void
    {
        global $wpdb;
        $prefix = $wpdb->esc_like('_transient_' . $this->prefix) . '%';
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $prefix));
        $prefixTimeout = $wpdb->esc_like('_transient_timeout_' . $this->prefix) . '%';
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $prefixTimeout));
    }
}
