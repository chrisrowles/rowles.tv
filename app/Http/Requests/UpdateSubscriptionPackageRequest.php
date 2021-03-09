<?php

namespace Rowles\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Rowles\Models\User;

class UpdateSubscriptionPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === User::ADMINISTRATOR;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:2|max:255',
            'description' => 'required|min:2|max:3000',
            'price_id' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'A title is required',
            'description.required' => 'A description is required'
        ];
    }
}
