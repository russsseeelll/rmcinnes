<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RecordRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_request_is_logged_with_an_anonymised_ip_only(): void
    {
        $this->seed();
        Log::spy();

        $this->withServerVariables(['REMOTE_ADDR' => '81.2.69.142'])->get('/cv')->assertOk();

        Log::shouldHaveReceived('info')->withArgs(fn ($message, $context) => $message === 'request'
            && $context['client'] === '81.2.69.0'
            && $context['path'] === '/cv'
            && $context['status'] === 200
            && ! str_contains(json_encode($context), '81.2.69.142'));
    }
}
