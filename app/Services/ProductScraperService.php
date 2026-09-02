<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ProductScraperService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
            ],
        ]);
    }

    /**
     * Scrape Product JSON-LD data from a given URL.
     *
     * @param string $url
     * @return array|null
     */
    public function scrapeProductLdJson(string $url): ?array
    {
        try {
            $response = $this->client->get($url);
            $html = (string) $response->getBody();
        } catch (GuzzleException $e) {
            Log::error("Failed to fetch URL: {$url}. Error: " . $e->getMessage());
            return null;
        }

        $jsonLdBlocks = $this->extractJsonLdBlocks($html);

        foreach ($jsonLdBlocks as $block) {
            $decoded = json_decode($block, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                continue;
            }

            // Handle @graph wrapping (common in some themes)
            $candidates = $decoded['@graph'] ?? [$decoded];

            foreach ($candidates as $item) {
                if (($item['@type'] ?? null) === 'Product') {
                    return $this->mapProductData($item);
                }
            }
        }

        return null;
    }

    /**
     * Extract all <script type="application/ld+json"> block contents.
     *
     * @param string $html
     * @return array<int, string>
     */
    protected function extractJsonLdBlocks(string $html): array
    {
        preg_match_all(
            '/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is',
            $html,
            $matches
        );

        return $matches[1] ?? [];
    }

    /**
     * Map raw JSON-LD product data to the required array shape.
     *
     * @param array $item
     * @return array
     */
    protected function mapProductData(array $item): array
    {
        $offers = $item['offers'] ?? [];

        // Some sites return offers as a list — grab the first if so
        if (isset($offers[0]) && is_array($offers)) {
            $offers = $offers[0];
        }

        return [
            'name' => $item['name'] ?? null,
            'url' => $item['url'] ?? null,
            'sku' => $item['sku'] ?? null,
            'productID' => $item['productID'] ?? null,
            'brand' => [
                'name' => $item['brand']['name'] ?? null,
            ],
            'description' => $item['description'] ?? null,
            'image' => $item['image'] ?? null,
            'offers' => [
                'priceCurrency' => $offers['priceCurrency'] ?? null,
                'price' => $offers['price'] ?? null,
                'itemCondition' => $offers['itemCondition'] ?? null,
                'availability' => $offers['availability'] ?? null,
                'url' => $offers['url'] ?? null,
                'image' => $offers['image'] ?? null,
                'name' => $offers['name'] ?? null,
                'sku' => $offers['sku'] ?? null,
                'description' => $offers['description'] ?? null,
            ],
        ];
    }
}