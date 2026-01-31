<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAiConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', 'in:openai,gemini,grok'],
            'api_key' => ['required', 'string', 'min:10'],
            'default_model' => ['nullable', 'string', 'max:100'],
            'is_enabled' => ['sometimes', 'boolean'],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }
}
