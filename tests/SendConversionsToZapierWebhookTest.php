<?php

namespace Agenciafmd\Zapier\Tests;

use Agenciafmd\Zapier\Jobs\SendConversionsToZapierWebhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendConversionsToZapierWebhookTest extends TestCase
{
    public function test_it_sends_data_to_zapier_webhook(): void
    {
        Http::fake([
            'https://hooks.zapier.com/*' => Http::response('success', 200),
        ]);

        config(['laravel-zapier.webhook' => 'https://hooks.zapier.com/hooks/catch/123']);

        $data = [
            'name' => 'João',
            'email' => 'joao@teste.com',
        ];

        (new SendConversionsToZapierWebhook($data))->handle();

        Http::assertSent(function ($request) use ($data) {
            return $request->url() === 'https://hooks.zapier.com/hooks/catch/123'
                && $request->isForm()
                && $request['name'] === 'João'
                && $request['email'] === 'joao@teste.com';
        });
    }

    public function test_it_does_not_send_when_webhook_is_empty(): void
    {
        Http::fake();

        config(['laravel-zapier.webhook' => '']);

        (new SendConversionsToZapierWebhook(['name' => 'Teste']))->handle();

        Http::assertNothingSent();
    }

    public function test_it_logs_the_response(): void
    {
        Http::fake([
            '*' => Http::response('ok', 200),
        ]);

        Log::shouldReceive('channel')
            ->with('zapier')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Zapier webhook'
                    && $context['status'] === 200
                    && $context['body'] === 'ok'
                    && $context['data'] === ['name' => 'Teste'];
            });

        config(['laravel-zapier.webhook' => 'https://hooks.zapier.com/hooks/catch/123']);

        (new SendConversionsToZapierWebhook(['name' => 'Teste']))->handle();
    }
}
