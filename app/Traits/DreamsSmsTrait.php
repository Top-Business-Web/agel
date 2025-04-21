<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait DreamsSmsTrait
{
    protected function getDreamsUser()
    {
        return config('services.dreams.user');
    }

    protected function getDreamsSecretKey()
    {
        return config('services.dreams.secret_key');
    }

    protected function getDreamsSender()
    {
        return config('services.dreams.sender', 'DefaultSender');
    }

    public function sendDreamsSms($to, $message)
    {
        try {
            $url = 'https://www.dreams.sa/index.php/api/sendsms/';

            $queryParams = [
                'user' => $this->getDreamsUser(),
                'secret_key' => $this->getDreamsSecretKey(),
                'sender' => $this->getDreamsSender(),
                'to' => $to,
                'message' => $message,
            ];

            $response = Http::timeout(30)->get($url, $queryParams);

            if ($response->successful()) {
                return $response->body(); // أو يمكنك return $response->body(); لو ما كان json
            }

            Log::error('Dreams SMS Send Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $url,
                'params' => $queryParams
            ]);

            throw new \Exception('Failed to send SMS. Status: ' . $response->status());

        } catch (\Exception $e) {
            Log::error('Dreams SMS Send Exception', ['error' => $e->getMessage()]);
            throw new \Exception('Failed to send SMS: ' . $e->getMessage());
        }
    }
}
