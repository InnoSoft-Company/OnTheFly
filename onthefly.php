<?php
/*
Plugin Name: OnTheFly
Plugin URI:
Description: Automatic content translation plugin connecting directly to translation providers.
Version: 1.0.0
Author: InnoSoft
Text Domain: onthefly
*/

if (!defined('ABSPATH')) {
    exit;
}

spl_autoload_register(function ($class) {
    $prefix = 'OnTheFly\\Core\\';
    $base_dir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

function onthefly_init()
{
    $plugin = new OnTheFly\Core\Plugin();
    $plugin->run();
}

add_action('plugins_loaded', 'onthefly_init');
