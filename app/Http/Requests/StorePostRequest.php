<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $business = $this->route('business');

        return $business && $this->user() && $this->user()->can('view', $business);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'caption' => ['required', 'string', 'max:65535'],
            'media_type' => ['nullable', 'string', 'in:text,image,video,carousel'],
            'media_url' => ['nullable', 'string', 'max:2048'],
            'media_prompt' => ['nullable', 'string', 'max:2048'],
            'content_pillar_id' => ['nullable', 'integer', Rule::exists('content_pillars', 'id')->where('business_account_id', $this->route('business')->id)],
            'channel' => ['nullable', 'string', 'max:50'],
        ];
    }
}
