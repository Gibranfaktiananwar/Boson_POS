<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalInventoryService
{
    protected $uid;
    protected $secret;
    protected $tokenUrl;
    protected $checkUrl;

    public function __construct()
    {
        $this->uid = env('EXTERNAL_API_UID');
        $this->secret = env('EXTERNAL_API_SECRET');
        $this->tokenUrl = env('EXTERNAL_API_TOKEN_URL');
        $this->checkUrl = env('EXTERNAL_API_CHECK_URL');
    }

    /**
     * Mendapatkan token dari cache atau generate baru
     */
    public function getToken()
    {
        return Cache::remember('external_inventory_token', 3600, function () {
            return $this->generateNewToken();
        });
    }

    /**
     * Generate token baru dari API
     */
    protected function generateNewToken()
    {
        try {
            $response = Http::withHeaders([
                'client_secret' => '',
            ])->asForm()->post($this->tokenUrl, [
                'uuid' => $this->uid,
                'secret' => $this->secret
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['token'] ?? null;
            }

            Log::error('Gagal mendapatkan token', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Exception saat generate token', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Cek SN barang
     */
    public function checkSn($sn)
    {
        $token = $this->getToken();

        if (!$token) {
            return [
                'status' => 'error',
                'message' => 'Gagal mendapatkan token'
            ];
        }

        try {
            $response = Http::asForm()->post($this->checkUrl, [
                'sn' => $sn,
                'token' => $token
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception saat check SN', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghubungi server'
            ];
        }
    }
}