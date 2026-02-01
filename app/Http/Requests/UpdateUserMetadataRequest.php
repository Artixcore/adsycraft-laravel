<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserMetadataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $metadata = $this->route('metadata');

        return $metadata && $this->user() && $metadata->user_id === $this->user()->id;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reference_type' => ['sometimes', 'string', 'in:env_file,config_file,custom'],
            'key' => ['sometimes', 'string', 'max:100'],
            'value' => ['sometimes', 'string'],
            'tags' => ['sometimes', 'nullable', 'array'],
            'tags.*' => ['string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'workspace_id' => ['sometimes', 'nullable', 'integer', 'exists:workspaces,id'],
        ];
    }
}
