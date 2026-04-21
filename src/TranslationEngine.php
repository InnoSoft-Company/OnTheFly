<?php

namespace OnTheFly\Core;

use DOMDocument;
use DOMXPath;
use OnTheFly\Core\Providers\TranslationProviderInterface;

class TranslationEngine
{
    private $cache;
    private $provider;

    public function __construct(Cache $cache, TranslationProviderInterface $provider)
    {
        $this->cache = $cache;
        $this->provider = $provider;
    }

    public function translateText($text, string $targetLanguage)
    {
        if (!is_string($text) || empty($text)) {
            return $text;
        }
        
        $cacheKey = md5($text . '_' . $targetLanguage);
        $cached = $this->cache->get($cacheKey);
        if ($cached !== false) {
            return $cached;
        }

        $translated = $this->provider->translate([$text], $targetLanguage);
        $result = $translated[0] ?? $text;

        $this->cache->set($cacheKey, $result);
        return $result;
    }

    public function translateHtml($html, string $targetLanguage)
    {
        if (!is_string($html) || empty(trim($html))) {
            return $html;
        }

        $dom = new DOMDocument();
        
        $internalErrors = libxml_use_internal_errors(true);
        
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        libxml_use_internal_errors($internalErrors);

        $xpath = new DOMXPath($dom);
        $textNodes = $xpath->query('//text()[not(ancestor::script) and not(ancestor::style)]');

        $textsToTranslate = [];
        $nodes = [];

        foreach ($textNodes as $node) {
            $text = trim($node->nodeValue);
            if (!empty($text)) {
                $textsToTranslate[] = $text;
                $nodes[] = $node;
            }
        }

        if (empty($textsToTranslate)) {
            return $dom->saveHTML();
        }

        $translatedTexts = $this->batchTranslate($textsToTranslate, $targetLanguage);

        foreach ($nodes as $index => $node) {
            if (isset($translatedTexts[$index])) {
                $node->nodeValue = htmlspecialchars($translatedTexts[$index], ENT_QUOTES, 'UTF-8');
            }
        }

        return $dom->saveHTML();
    }

    private function batchTranslate(array $texts, string $targetLanguage): array
    {
        $translatedTexts = [];
        $textsToApi = [];
        $indexesToApi = [];

        foreach ($texts as $index => $text) {
            $cacheKey = md5($text . '_' . $targetLanguage);
            $cached = $this->cache->get($cacheKey);
            if ($cached !== false) {
                $translatedTexts[$index] = $cached;
            } else {
                $textsToApi[] = $text;
                $indexesToApi[] = $index;
            }
        }

        if (!empty($textsToApi)) {
            $chunks = array_chunk($textsToApi, 50);
            $chunkIndexes = array_chunk($indexesToApi, 50);

            foreach ($chunks as $chunkKey => $chunk) {
                $apiResults = $this->provider->translate($chunk, $targetLanguage);
                foreach ($apiResults as $i => $translatedPart) {
                    $originalIndex = $chunkIndexes[$chunkKey][$i];
                    $translatedTexts[$originalIndex] = $translatedPart;
                    $cacheKey = md5($chunk[$i] . '_' . $targetLanguage);
                    $this->cache->set($cacheKey, $translatedPart);
                }
            }
        }

        ksort($translatedTexts);
        return $translatedTexts;
    }
}
