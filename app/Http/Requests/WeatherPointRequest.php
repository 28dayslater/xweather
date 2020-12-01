<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Validation\Validator;

class WeatherPointRequest extends FormRequest
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
            'date' => 'required|date',
            'location.city' => 'required',
            'location.state' => 'required',
            'location.lat' => 'required|numeric',
            'location.lon' => 'required|numeric',
            // It would be easy to require all 24 values. Let's just make it
            // a little more interesting by requiring some to be present
            // (See withValidator())
            'temperature.*' => 'nullable|numeric'
        ];
    }

    /**
     * Ensure that there is at least some temperature points populated.
     */
    public function withValidator($validator)
    {

    }

    public function messages()
    {
        return [
            'date.required' => 'Required field',
            'date.date' => 'Invalid date',
            'location.city.required' => 'Required field',
            'location.state.required' => 'Required field',
            'location.lon.required' => 'Required field',
            'location.lat.required' => 'Required field',
            'temperature.numeric' => 'Invalid temperature'
        ];
    }
}
