<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestRedisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $message = 'Hello from Redis queue!')
    {
    }

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info("TestRedisJob executed: {$this->message}");
        echo "✓ Job executed: {$this->message}\n";
    }
}
