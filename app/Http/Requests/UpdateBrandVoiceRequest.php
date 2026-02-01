<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandVoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'tone' => ['sometimes', 'nullable', 'string', 'max:100'],
            'style' => ['sometimes', 'nullable', 'string', 'max:100'],
            'keywords' => ['sometimes', 'nullable', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'avoid_words' => ['sometimes', 'nullable', 'array'],
            'avoid_words.*' => ['string', 'max:255'],
            'compliance_rules' => ['sometimes', 'nullable', 'array'],
            'language' => ['sometimes', 'nullable', 'string', 'max:10'],
            'meta_asset_id' => ['sometimes', 'nullable', 'integer', 'exists:meta_assets,id'],
        ];
    }
}
