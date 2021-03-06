<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:places,slug|string|max:255',
            'description' => 'required|string',
            'photos.*' => 'required|image',
            'duration' => 'required|integer',
            'places.*' => 'required|integer',
            'startPlace' => 'required|integer',
            'finishPlace' => 'required|integer'
        ];
    }
}
