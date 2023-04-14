<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateCrudRequest extends FormRequest
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
        $id = $this->get('id') ?? request()->route('id');

        return [
            'email'    => 'required|email:rfc,dns|unique:'.config('permission.table_names.users', 'users').',email,'.$id,
            'name'     => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?& ]{8,}$/u',
            'password_confirmation' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?& ]{8,}$/u',
            'roles_show' => 'required',
            //'permissions_show' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.regex' => 'The name field can only contain alphanumeric characters.',
            'password.regex' => 'Password must consist of minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.',
            'password_confirmation.regex' => 'Confirm Password must consist of minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.',
            'roles_show.required' => 'The roles field is required',
            //'permissions_show.required' => 'The permission field is required'
        ];
    }
}
