<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'slug' => 'required|string|unique:places,slug|max:255',
            'description' => 'required|string',
            'rating' => 'required|integer',
            'photos.*' => 'required|image',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ];
    }
}
