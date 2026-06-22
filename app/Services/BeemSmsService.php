<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class BeemSmsService
{
    public function isConfigured(): bool
    {
        return $this->apiKey() !== ''
            && $this->secretKey() !== ''
            && $this->senderId() !== '';
    }

    public function normalizeDestination(string $phone, string $countryCode): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        $countryDigits = preg_replace('/\D+/', '', $countryCode) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if ($countryDigits !== '' && str_starts_with($digits, $countryDigits)) {
            $normalized = $digits;
        } elseif (str_starts_with($digits, '0')) {
            $normalized = $countryDigits.ltrim($digits, '0');
        } elseif ($countryDigits !== '') {
            $normalized = $countryDigits.$digits;
        } else {
            $normalized = $digits;
        }

        if ($normalized === '' || strlen($normalized) < 10 || strlen($normalized) > 15) {
            return '';
        }

        return $normalized;
    }

    public function send(string $destination, string $message, string $recipientId = '1'): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Beem SMS is not configured.');
        }

        $response = Http::withBasicAuth($this->apiKey(), $this->secretKey())
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->post($this->sendUrl(), [
                'source_addr' => $this->senderId(),
                'schedule_time' => '',
                'encoding' => 0,
                'message' => $message,
                'recipients' => [[
                    'recipient_id' => $recipientId,
                    'dest_addr' => $destination,
                ]],
            ]);

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('Beem SMS returned an invalid response.');
        }

        if (! $response->successful() || ($payload['successful'] ?? false) !== true) {
            $message = is_string($payload['message'] ?? null) && $payload['message'] !== ''
                ? $payload['message']
                : 'Beem SMS rejected the request.';

            throw new RuntimeException($message);
        }

        return $payload;
    }

    private function sendUrl(): string
    {
        return (string) config('services.beem.send_url', 'https://apisms.beem.africa/v1/send');
    }

    private function apiKey(): string
    {
        return trim((string) config('services.beem.api_key', ''));
    }

    private function secretKey(): string
    {
        return trim((string) config('services.beem.secret_key', ''));
    }

    private function senderId(): string
    {
        return trim((string) config('services.beem.sender_id', ''));
    }
}
