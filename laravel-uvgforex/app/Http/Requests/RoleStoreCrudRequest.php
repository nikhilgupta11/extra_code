<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreCrudRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9 -]+$/u|unique:'.config('permission.table_names.roles', 'roles').',name',
            'permissions' => 'required',
            'permissions_show' => 'required',
        ];

        return $rules;
    }
    public function messages()
    {
        return [
            'name.regex' => 'The name field can only contain alphanumeric characters.',
            'permissions_show.required' => 'The permission field is required'
        ];
    }
}
