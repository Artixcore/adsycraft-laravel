<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublishPostRequest extends FormRequest
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
        $business = $this->route('business');

        return [
            'meta_asset_id' => [
                'required',
                'integer',
                Rule::exists('meta_assets', 'id')->where('business_account_id', $business->id),
            ],
        ];
    }
}
