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
        $error = $request->query('error');
        $errorReason = $request->query('error_reason');
        $errorDescription = $request->query('error_description');

        if ($error) {
            $reason = $errorDescription ?: $errorReason ?: $error;

            return redirect()->route('dashboard.connectors', [
                'meta' => 'error',
                'reason' => $reason,
            ])->with('error', $reason);
        }

        $code = $request->query('code');
        $state = $request->query('state');

        if (! $code || ! $state) {
            return redirect()->route('dashboard.connectors', [
                'meta' => 'error',
                'reason' => 'Missing code or state.',
            ])->with('error', 'Missing code or state.');
        }

        try {
            $result = $metaOAuthService->handleCallback($request, $code, $state);
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('dashboard.connectors', [
                'meta' => 'error',
                'reason' => $e->getMessage(),
            ])->with('error', $e->getMessage());
        } catch (\RuntimeException $e) {
            return redirect()->route('dashboard.connectors', [
                'meta' => 'error',
                'reason' => $e->getMessage(),
            ])->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return redirect()->route('dashboard.connectors', [
                'meta' => 'error',
                'reason' => 'Connection failed. Please try again.',
            ])->with('error', 'Connection failed. Please try again.');
        }

        $connection = $result['connection'];
        $accessToken = $result['access_token'] ?? $connection->access_token;
        if (! $accessToken) {
            return redirect()->route('dashboard.connectors', [
                'meta' => 'error',
                'reason' => 'No access token received.',
            ])->with('error', 'No access token received.');
        }

        $graph = MetaGraphService::forToken($accessToken);
        $graph->storeAssets($connection->businessAccount);

        return redirect()->route('dashboard.connectors', ['meta' => 'connected'])
            ->with('success', 'Meta account connected. Select your pages below.');
    }
}
