<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workspace_id' => ['nullable', 'integer', 'exists:workspaces,id'],
            'name' => ['required', 'string', 'max:255'],
            'niche' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'tone' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'string', 'max:10'],
            'timezone' => ['required', 'string', 'timezone'],
            'posts_per_day' => ['nullable', 'integer', 'min:1', 'max:20'],
            'autopilot_enabled' => ['sometimes', 'boolean'],
            'meta_page_id' => ['nullable', 'string', 'max:255'],
            'settings' => ['nullable', 'array'],
        ];
    }
}
