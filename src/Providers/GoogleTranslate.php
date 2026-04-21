<?php

namespace OnTheFly\Core\Providers;

class GoogleTranslate implements TranslationProviderInterface
{
    public function translate(array $texts, string $targetLanguage): array
    {
        $apiKey = get_option('onthefly_google_api_key', '');
        if (empty($apiKey)) {
            return $texts;
        }

        $url = 'https://translation.googleapis.com/language/translate/v2?key=' . $apiKey;
        
        $body = [
            'q' => $texts,
            'target' => $targetLanguage,
            'format' => 'html'
        ];

        $response = wp_remote_post($url, [
            'body' => wp_json_encode($body),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            return $texts;
        }

        $responseBody = wp_remote_retrieve_body($response);
        $data = json_decode($responseBody, true);

        if (isset($data['data']['translations']) && is_array($data['data']['translations'])) {
            $results = [];
            foreach ($data['data']['translations'] as $translation) {
                $results[] = $translation['translatedText'];
            }
            return $results;
        }

        return $texts;
    }
}
