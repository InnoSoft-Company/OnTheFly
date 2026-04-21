<?php

namespace OnTheFly\Core\Providers;

class DeepL implements TranslationProviderInterface
{
  public function translate(array $texts, string $targetLanguage): array
  {
    $apiKey = get_option('onthefly_deepl_api_key', '');
    if (empty($apiKey)) {
      return $texts;
    }

    $url = strpos($apiKey, ':fx') !== false 
      ? 'https://api-free.deepl.com/v2/translate' 
      : 'https://api.deepl.com/v2/translate';
    
    $body = [
      'text' => $texts,
      'target_lang' => strtoupper($targetLanguage),
      'tag_handling' => 'html'
    ];

    $response = wp_remote_post($url, [
      'body' => wp_json_encode($body),
      'headers' => [
        'Authorization' => 'DeepL-Auth-Key ' . $apiKey,
        'Content-Type' => 'application/json',
      ],
      'timeout' => 15
    ]);

    if (is_wp_error($response)) {
      return $texts;
    }

    $responseBody = wp_remote_retrieve_body($response);
    $data = json_decode($responseBody, true);

    if (isset($data['translations']) && is_array($data['translations'])) {
      $results = [];
      foreach ($data['translations'] as $translation) {
        $results[] = $translation['text'];
      }
      return $results;
    }

    return $texts;
  }
}
