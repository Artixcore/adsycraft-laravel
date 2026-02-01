<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserMetadataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reference_type' => ['nullable', 'string', 'in:env_file,config_file,custom'],
            'key' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'workspace_id' => ['nullable', 'integer', 'exists:workspaces,id'],
        ];
    }
}
