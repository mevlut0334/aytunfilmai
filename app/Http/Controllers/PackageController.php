<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PackageController extends Controller
{
    /**
     * Paddle API'den fiyat bilgisini çek (cache'li)
     * Fiyatlar 60 dakika cache'lenir, Paddle'da değişince cache temizlenir.
     */
    private function fetchPaddlePrices(array $priceIds): array
    {
        if (empty($priceIds)) {
            return [];
        }

        $cacheKey = 'paddle_prices_' . md5(implode(',', $priceIds));

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($priceIds) {
            try {
                $apiKey  = config('cashier.api_key');
                $sandbox = config('cashier.sandbox');
                $baseUrl = $sandbox
                    ? 'https://sandbox-api.paddle.com'
                    : 'https://api.paddle.com';

                // Paddle prices endpoint — virgülle ayrılmış ID listesi
                $response = Http::withToken($apiKey)
                    ->get("{$baseUrl}/prices", [
                        'id' => $priceIds,
                    ]);

                if (!$response->successful()) {
                    Log::error('Paddle API fiyat çekme hatası', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                    return [];
                }

                $prices = [];
                foreach ($response->json('data', []) as $price) {
                    $priceId = $price['id'] ?? null;
                    if (!$priceId) continue;

                    // unit_price.amount kuruş cinsinden gelir (örn: 999 = $9.99)
                    $amount   = $price['unit_price']['amount']   ?? 0;
                    $currency = $price['unit_price']['currency_code'] ?? 'USD';

                    $prices[$priceId] = [
                        'amount'   => $amount / 100,
                        'currency' => $currency,
                    ];
                }

                return $prices;

            } catch (\Exception $e) {
                Log::error('Paddle API bağlantı hatası: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Paket listesi (Kullanıcı tarafı)
     * Aktif paketleri getirir, Paddle'dan fiyatları ekler.
     */
    public function index(): View
    {
        $packages = Package::active()->sorted()->get();

        // Paddle price ID'lerini topla
        $priceIds = $packages
            ->whereNotNull('paddle_price_id')
            ->pluck('paddle_price_id')
            ->filter()
            ->values()
            ->toArray();

        // Paddle'dan fiyatları çek
        $paddlePrices = $this->fetchPaddlePrices($priceIds);

        // Her pakete paddle_price ve paddle_currency ekle
        $packages->each(function ($package) use ($paddlePrices) {
            $pid = $package->paddle_price_id;
            $package->paddle_price    = $paddlePrices[$pid]['amount']   ?? null;
            $package->paddle_currency = $paddlePrices[$pid]['currency'] ?? 'USD';
        });

        return view('packages.index', compact('packages'));
    }
}
