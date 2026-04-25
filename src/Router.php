<?php

namespace OnTheFly\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Router
{
    public function registerHooks(): void
    {
        add_filter('query_vars', [$this, 'addQueryVars']);
    }

    public function addQueryVars(array $vars): array
    {
        $vars[] = 'lang';
        return $vars;
    }

    public function getTargetLanguage(): ?string
    {
        $lang = get_query_var('lang');
        if (!$lang && isset($_GET['lang'])) {
            $lang = sanitize_text_field(wp_unslash($_GET['lang']));
        }

        if ($lang && $this->isValidLanguageCode($lang)) {
            return $lang;
        }

        return null;
    }

    private function isValidLanguageCode(string $code): bool
    {
        return preg_match('/^[a-z]{2}(-[a-z]{2})?$/i', $code) === 1;
    }
}
