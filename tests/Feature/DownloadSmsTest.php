<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DownloadSmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_sms_endpoint_sends_both_store_links_via_beem(): void
    {
        config()->set('services.beem.send_url', 'https://apisms.beem.africa/v1/send');
        config()->set('services.beem.api_key', 'test-api-key');
        config()->set('services.beem.secret_key', 'test-secret-key');
        config()->set('services.beem.sender_id', 'SKELAPP');
        config()->set('services.beem.apple_url', 'https://apps.apple.com/app/skelapp-ios');
        config()->set('services.beem.google_url', 'https://play.google.com/store/apps/details?id=com.skelapp');

        Http::fake([
            'https://apisms.beem.africa/v1/send' => Http::response([
                'successful' => true,
                'request_id' => 67,
                'code' => 100,
                'message' => 'Message Submitted Successfully',
                'valid' => 1,
                'invalid' => 0,
                'duplicates' => 0,
            ], 200),
        ]);

        $this->postJson(route('download.sms'), [
            'phone' => '0712 345 678',
            'country_code' => '+255',
        ])
            ->assertOk()
            ->assertJson([
                'message' => "We've sent the App Store and Google Play links to your phone.",
            ]);

        Http::assertSent(function (HttpRequest $request) {
            return $request->url() === 'https://apisms.beem.africa/v1/send'
                && $request->hasHeader('Authorization', 'Basic '.base64_encode('test-api-key:test-secret-key'))
                && data_get($request->data(), 'source_addr') === 'SKELAPP'
                && data_get($request->data(), 'encoding') === 0
                && data_get($request->data(), 'recipients.0.dest_addr') === '255712345678'
                && str_contains((string) data_get($request->data(), 'message'), 'https://apps.apple.com/app/skelapp-ios')
                && str_contains((string) data_get($request->data(), 'message'), 'https://play.google.com/store/apps/details?id=com.skelapp');
        });
    }

    public function test_download_sms_endpoint_rejects_an_invalid_phone_number(): void
    {
        Http::fake();

        $this->postJson(route('download.sms'), [
            'phone' => '12',
            'country_code' => '+255',
        ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Please enter a valid mobile number.',
            ]);

        Http::assertNothingSent();
    }
}
