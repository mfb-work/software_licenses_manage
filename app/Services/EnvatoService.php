<?php

namespace App\Services;

use App\Models\EnvatoApiSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EnvatoService
{
    protected Client $client;
    protected string $token;

    public function __construct()
    {
        $setting = EnvatoApiSetting::first();
        $this->token = $setting ? $setting->token : (env('ENVATO_API_TOKEN') ?: '');
        $this->client = new Client([
            'base_uri' => 'https://api.envato.com',
            'timeout'  => 10.0
        ]);
        // dd($setting, $this);
    }

    public function verifyPurchase(string $purchaseCode): ?array
    {
        $cacheKey = 'purchase_verification_' . $purchaseCode;
        // dd($cacheKey,$this->token);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = $this->client->get('/v3/market/author/sale', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'query' => [
                    'code' => $purchaseCode
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Cache::put($cacheKey, $data, now()->addHours(24));

            return $data;
        } catch (\Exception $e) {
            Log::error('EnvatoService verifyPurchase error: ' . $e->getMessage());
            return null;
        }
    }

    public function getItem(int $itemId): ?array
    {
        $cacheKey = 'envato_item_' . $itemId;
        return Cache::remember($cacheKey, 86400, function () use ($itemId) {
            try {
                $response = $this->client->get('/v3/market/catalog/item', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                    ],
                    'query' => [
                        'id' => $itemId
                    ]
                ]);

                return json_decode($response->getBody()->getContents(), true);
            } catch (\Exception $e) {
                Log::error('EnvatoService getItem error: ' . $e->getMessage());
                return null;
            }
        });
    }
}
