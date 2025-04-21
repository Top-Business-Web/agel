<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait DreamsSmsTrait
{
    protected function getDreamsClientId()
    {
        return config('services.dreams.client_id');
    }

    protected function getDreamsClientSecret()
    {
        return config('services.dreams.client_secret');
    }

    protected function getDreamsSender()
    {
        return config('services.dreams.sender', 'DefaultSender');
    }

    protected function getDreamsToken()
    {
        return Cache::remember('dreams_sms_token', 3500, function () {
            try {
                $response = Http::asForm()
                    ->timeout(30)
                    ->retry(3, 100)
                    ->post('https://www.dreams.sa/oauth/token', [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->getDreamsClientId(),
                        'client_secret' => $this->getDreamsClientSecret(),
                    ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                Log::error('Dreams SMS Token Request Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers()
                ]);

                throw new \Exception('Failed to get Dreams SMS token. Status: ' . $response->status());

            } catch (\Exception $e) {
                Log::error('Dreams SMS Token Exception', ['error' => $e->getMessage()]);
                throw new \Exception('Dreams SMS Service Unavailable: ' . $e->getMessage());
            }
        });
    }

    public function sendDreamsSms($to, $message)
    {
        try {
            $token = $this->getDreamsToken();

            $response = Http::withToken($token)
                ->timeout(30)
                ->retry(2, 100)
                ->post('https://www.dreams.sa/api/sms/send', [
                    'to' => $to,
                    'message' => $message,
                    'sender' => $this->getDreamsSender(),
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Dreams SMS Send Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);

            throw new \Exception('Failed to send SMS. Status: ' . $response->status());

        } catch (\Exception $e) {
            Log::error('Dreams SMS Send Exception', ['error' => $e->getMessage()]);
            throw new \Exception('Failed to send SMS: ' . $e->getMessage());
        }
    }
}
