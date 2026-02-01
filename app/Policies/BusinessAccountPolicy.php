<?php

namespace App\Policies;

use App\Models\BusinessAccount;
use App\Models\User;

class BusinessAccountPolicy
{
    public function view(User $user, BusinessAccount $businessAccount): bool
    {
        return $businessAccount->user_id === $user->id
            || ($businessAccount->workspace_id && $user->workspaces()->where('workspaces.id', $businessAccount->workspace_id)->exists());
    }

    public function update(User $user, BusinessAccount $businessAccount): bool
    {
        return $businessAccount->user_id === $user->id
            || ($businessAccount->workspace_id && $user->workspaces()->where('workspaces.id', $businessAccount->workspace_id)->exists());
    }

    public function delete(User $user, BusinessAccount $businessAccount): bool
    {
        return $businessAccount->user_id === $user->id
            || ($businessAccount->workspace_id && $user->workspaces()->where('workspaces.id', $businessAccount->workspace_id)->exists());
    }
}
