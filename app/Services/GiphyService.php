<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GiphyService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GIPHY_API_KEY');
    }

    public function searchGifs(string $query, int $limit = 25, int $offset = 0)
    {
        $response = Http::get('http://api.giphy.com/v1/gifs/search', [
            'api_key' => $this->apiKey,
            'q' => $query,
            'limit' => $limit,
            'offset' => $offset
        ]);

        return $response->json();
    }

    public function getGifById(string $id)
    {
        $response = Http::get("http://api.giphy.com/v1/gifs/{$id}", [
            'api_key' => $this->apiKey
        ]);

        return $response->json();
    }
}
