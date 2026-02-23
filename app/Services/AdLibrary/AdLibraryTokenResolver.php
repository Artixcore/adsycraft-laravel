<?php

namespace App\Services\AdLibrary;

use App\Models\OAuthConnection;
use App\Models\User;

class AdLibraryTokenResolver
{
    public function resolveForUser(User $user): ?string
    {
        if (config('services.meta.stub', false)) {
            return 'stub_token_for_testing';
        }

        $envToken = config('services.meta.ad_library.access_token');
        if (! empty($envToken)) {
            return $envToken;
        }

        $connection = OAuthConnection::query()
            ->where('provider', OAuthConnection::PROVIDER_META)
            ->whereHas('businessAccount', fn ($q) => $q->where('user_id', $user->id))
            ->whereNotNull('access_token')
            ->first();

        if (! $connection) {
            return null;
        }

        return $connection->access_token;
    }

    public function hasToken(User $user): bool
    {
        return $this->resolveForUser($user) !== null;
    }
}
