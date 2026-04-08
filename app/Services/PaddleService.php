<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaddleService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('cashier.api_key');
        $this->baseUrl = config('cashier.sandbox')
            ? 'https://sandbox-api.paddle.com'
            : 'https://api.paddle.com';
    }

    /**
     * Paddle'dan fiyat bilgisini çek — 5 dakika cache'li
     */
    public function getPrice(string $priceId): ?array
    {
        return Cache::remember("paddle_price_{$priceId}", now()->addMinutes(5), function () use ($priceId) {
            return $this->fetchFromApi($priceId);
        });
    }

    /**
     * Paddle fiyat cache'ini temizle
     * Admin paketi güncellediğinde çağrılır
     */
    public function clearPriceCache(string $priceId): void
    {
        Cache::forget("paddle_price_{$priceId}");
    }

    private function fetchFromApi(string $priceId): ?array
    {
        try {
            $response = Http::withHeaders([
                     'Authorization' => 'Bearer ' . $this->apiKey,
                          ])->get("{$this->baseUrl}/prices/{$priceId}");

            if ($response->successful()) {
                $data = $response->json('data');

                return [
                    'amount'   => (float) $data['unit_price']['amount'] / 100, // 999 → 9.99
                    'currency' => $data['unit_price']['currency_code'],         // "USD"
                ];
            }

            Log::warning('Paddle fiyat alınamadı', [
                'price_id' => $priceId,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Paddle API hatası: ' . $e->getMessage());
            return null;
        }
    }
}
