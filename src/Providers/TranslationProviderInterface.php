<?php

namespace OnTheFly\Core\Providers;

interface TranslationProviderInterface
{
    public function translate(array $texts, string $targetLanguage): array;
}
