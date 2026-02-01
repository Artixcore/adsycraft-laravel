<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkspaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $workspace = $this->route('workspace');

        return $workspace && $this->user() && $workspace->users()->where('user_id', $this->user()->id)->exists();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $workspace = $this->route('workspace');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'alpha_dash', 'unique:workspaces,slug,'.$workspace?->id],
            'subscription_tier' => ['sometimes', 'string', 'in:free,pro,enterprise'],
            'subscription_status' => ['sometimes', 'string', 'in:active,cancelled,past_due,trialing'],
            'subscription_expires_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
