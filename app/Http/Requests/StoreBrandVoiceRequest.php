<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandVoiceRequest extends FormRequest
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
        return [
            'tone' => ['nullable', 'string', 'max:100'],
            'style' => ['nullable', 'string', 'max:100'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'avoid_words' => ['nullable', 'array'],
            'avoid_words.*' => ['string', 'max:255'],
            'compliance_rules' => ['nullable', 'array'],
            'language' => ['nullable', 'string', 'max:10'],
            'meta_asset_id' => ['nullable', 'integer', 'exists:meta_assets,id'],
        ];
    }
}
