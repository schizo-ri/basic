<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title' 			=> 'required|max:255',
            'date' 				=> 'required',
            'time1' 			=> 'required',
            'time2' 			=> 'required',
            'description' 		=> 'required|max:65535',
        ];
    }
}