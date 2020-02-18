<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'required|unique:places|max:255',
            'description' => 'required',
            'rating' => 'required|integer',
            'photos' => 'required|image',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ];
    }
}
