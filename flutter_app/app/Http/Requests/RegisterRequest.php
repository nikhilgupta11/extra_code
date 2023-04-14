<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'integer',
            'email' => 'required|email|unique:users,email',
            'password' => ['required','min:8',Rules\Password::defaults()]
        ];
    }

    public function failedValidation(Validator $validator)
    {
        // send error message
        $response = ['status'=>'error','message' => 'validation error'];
        $errors = $validator->errors();
        if(!empty($errors)){
            $response['data'] = $errors;
        }

        throw new HttpResponseException(response()->json($response,401));

    }
}
