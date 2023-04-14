<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:currency,name,'.$this->id.'|min:3|max:255|regex:/^[a-zA-Z0-9 -]+$/u',
            'exchange_rate' => 'required|gt:0|max:9999|regex:/^[0-9 .]+$/u',
            'currency_category_id' =>'required', 
            'code' => 'required|unique:currency,code,'.$this->id.'|regex:/^[A-Za-z -]+$/u|size:3',
            'status' => 'required|in:0,1',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'currency_category_id.required' => 'Category is required',
            'status.in' => 'Status is required',
            'exchange_rate.regex' => 'Only numeric values allowed.',
            'exchange_rate.gt' => 'UVG value must be greater than 0.',
            'exchange_rate.required' => 'UVG value is required.',
            'exchange_rate.max' => 'UVG value must not be greater than 9999.',
            'code.regex' => 'Only alphabetic values are allowed for code',
            'code.size' => 'Code must be in 3 characters only'
        ];
    }
}
