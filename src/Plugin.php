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
    $this->translationEngine = new TranslationEngine(
      new Cache(),
      new Providers\GoogleTranslate()
    );
    $this->settings = new Admin\Settings();
  }

  public function run(): void
  {
    $this->settings->registerHooks();
    $this->router->registerHooks();
    
    add_filter('the_content', [$this, 'filterContent'], 999);
    add_filter('the_title', [$this, 'filterTitle'], 999);
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
}
