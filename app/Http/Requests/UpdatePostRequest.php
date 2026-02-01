<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $business = $this->route('business');

        return $business && $this->user() && $this->user()->can('update', $business);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'caption' => ['sometimes', 'string', 'max:65535'],
            'media_type' => ['sometimes', 'nullable', 'string', 'in:text,image,video,carousel'],
            'media_url' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'media_prompt' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'content_pillar_id' => ['sometimes', 'nullable', 'integer', Rule::exists('content_pillars', 'id')->where('business_account_id', $this->route('business')->id)],
            'channel' => ['sometimes', 'nullable', 'string', 'max:50'],
            'status' => ['sometimes', 'string', 'in:draft,scheduled,publishing,published,failed,cancelled'],
        ];
    }
}
