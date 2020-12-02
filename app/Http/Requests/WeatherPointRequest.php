<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Validation\Validator;
use Log;

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
            'temperature.*' => 'required|numeric'
        ];
    }

    /**
     * Ensure that there is exactly 24 temperature points.
     */
    public function withValidator($validator)
    {
        $validator->after(function($validator) {
            if (count($validator->getData()['temperature']) !== 24) {
                $validator->errors()->add('temperature', 'Temperature must contain exactly 24 values');
            }
        });
    }

    public function messages()
    {
        return [
            'date.required' => 'Required',
            'date.date' => 'Invalid date',
            'location.city.required' => 'Required',
            'location.state.required' => 'Required',
            'location.lon.required' => 'Required',
            'location.lat.required' => 'Required',
            'location.lat.numeric' => 'Invalid lat',
            'location.lon.numeric' => 'Invalid lon',
            'temperature.*.required' => 'Requiured',
            'temperature.*.numeric' => 'Invalid'
        ];
    }
}
