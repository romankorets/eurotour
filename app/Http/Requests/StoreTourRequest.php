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
            'photos.*' => 'required|image',
            'duration' => 'required|integer',
            'places.*' => 'required|integer',
            'startPlace' => 'required|integer',
            'finishPlace' => 'required|integer'
        ];
    }
}
