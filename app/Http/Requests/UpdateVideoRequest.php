<?php

namespace Rowles\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:2|max:255',
            'genre' => 'required|min:2|max:255',
            'producer' => 'required|min:2|max:255',
            'description' => 'required|min:2'
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
            'title.required' => 'A title is required',
            'genre.required' => 'A genre is required',
            'producer.required' => 'A producer is required',
            'description.required' => 'A description is required',
        ];
    }
}
