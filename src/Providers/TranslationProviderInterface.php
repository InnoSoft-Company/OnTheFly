<?php

namespace OnTheFly\Core\Providers;

if (!defined('ABSPATH')) {
    exit;
}

interface TranslationProviderInterface
{
    public function translate(array $texts, string $targetLanguage): array;
}
