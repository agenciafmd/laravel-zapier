<?php

namespace Agenciafmd\Zapier\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendConversionsToZapierWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        if (!config('laravel-zapier.webhook')) {
            return;
        }

        $response = Http::timeout(60)
            ->connectTimeout(60)
            ->withoutVerifying()
            ->asForm()
            ->post(config('laravel-zapier.webhook'), $this->data);

        Log::channel('zapier')->info('Zapier webhook', [
            'status' => $response->status(),
            'body' => $response->body(),
            'data' => $this->data,
        ]);
    }
}
