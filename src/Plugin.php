<?php

namespace OnTheFly\Core;

class Plugin
{
  private $router;
  private $translationEngine;
  private $settings;

  public function __construct()
  {
    $this->router = new Router();
    $this->settings = new Admin\Settings();
    
    $activeProviderStr = get_option('onthefly_active_provider', 'google');
    $provider = $activeProviderStr === 'deepl' 
      ? new Providers\DeepL() 
      : new Providers\GoogleTranslate();

    $this->translationEngine = new TranslationEngine(
      new Cache(),
      $provider
    );
  }

  public function run(): void
  {
    $this->settings->registerHooks();
    $this->router->registerHooks();
    
    add_filter('the_content', [$this, 'filterContent'], 999);
    add_filter('the_title', [$this, 'filterTitle'], 999);
    add_filter('language_attributes', [$this, 'filterLanguageAttributes'], 999);
    add_filter('document_title_parts', [$this, 'filterDocumentTitle'], 999);
  }

  public function filterContent(string $content): string
  {
    $targetLanguage = $this->router->getTargetLanguage();
    if (!$targetLanguage) {
      return $content;
    }
    return $this->translationEngine->translateHtml($content, $targetLanguage);
  }

  public function filterTitle(string $title): string
  {
    $targetLanguage = $this->router->getTargetLanguage();
    if (!$targetLanguage) {
      return $title;
    }
    return $this->translationEngine->translateText($title, $targetLanguage);
  }

  public function filterLanguageAttributes(string $attributes): string
  {
    $targetLanguage = $this->router->getTargetLanguage();
    if (!$targetLanguage) {
      return $attributes;
    }
    return 'lang="' . esc_attr($targetLanguage) . '"';
  }

  public function filterDocumentTitle(array $titleParts): array
  {
    $targetLanguage = $this->router->getTargetLanguage();
    if (!$targetLanguage || empty($titleParts['title'])) {
      return $titleParts;
    }
    $titleParts['title'] = $this->translationEngine->translateText($titleParts['title'], $targetLanguage);
    return $titleParts;
  }
}
