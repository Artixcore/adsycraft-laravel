<?php

namespace App\Http\Controllers;

use App\Services\Meta\MetaGraphService;
use App\Services\Meta\MetaOAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MetaOAuthCallbackController extends Controller
{
    public function __invoke(Request $request, MetaOAuthService $metaOAuthService): RedirectResponse
    {
        $code = $request->query('code');
        $state = $request->query('state');

        if (! $code || ! $state) {
            return redirect()->route('dashboard.connectors', ['error' => 'missing_params'])
                ->with('error', 'Missing code or state.');
        }

        try {
            $result = $metaOAuthService->handleCallback($code, $state);
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('dashboard.connectors', ['error' => 'invalid_state'])
                ->with('error', $e->getMessage());
        } catch (\RuntimeException $e) {
            return redirect()->route('dashboard.connectors', ['error' => 'token_exchange'])
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return redirect()->route('dashboard.connectors', ['error' => 'unknown'])
                ->with('error', 'Connection failed. Please try again.');
        }

        $connection = $result['connection'];
        $accessToken = $result['access_token'] ?? $connection->access_token;
        if (! $accessToken) {
            return redirect()->route('dashboard.connectors', ['error' => 'no_token'])
                ->with('error', 'No access token received.');
        }

        $graph = MetaGraphService::forToken($accessToken);
        $graph->storeAssets($connection->businessAccount);

        return redirect()->route('dashboard.connectors', ['connected' => 1])
            ->with('success', 'Meta account connected. Select your pages below.');
    }
}
