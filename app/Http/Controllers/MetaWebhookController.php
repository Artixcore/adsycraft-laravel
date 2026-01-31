<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MetaWebhookController extends Controller
{
    public function verify(Request $request): Response
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $expectedToken = config('services.meta.webhook_verify_token');

        if ($mode === 'subscribe' && $expectedToken && $token === $expectedToken) {
            return response($challenge ?? '', 200);
        }

        return response('', 403);
    }

    public function handle(Request $request): Response
    {
        Log::info('Meta webhook payload', $request->all());

        return response('', 200);
    }
}
