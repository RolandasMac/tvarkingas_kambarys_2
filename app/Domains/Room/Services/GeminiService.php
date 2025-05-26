<?php
namespace App\Domains\Room\Services;

use GuzzleHttp\Client;

class GeminiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/models/',
        ]);
    }

    public function analyzeImage(string $imagePath, string $prompt = 'Apibūdink šią nuotrauką')
    {
        $imageData = base64_encode(file_get_contents($imagePath));

        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => 'image/jpeg',
                                'data'      => $imageData,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->client->post("gemini-1.5-flash/generateContent", [
            'query' => ['key' => $this->apiKey],
            'json'  => $body,
        ]);

        $json = json_decode($response->getBody(), true);

        return $json['candidates'][0]['content']['parts'][0]['text'] ?? 'Nėra atsakymo';
    }
}
