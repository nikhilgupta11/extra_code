<?php

namespace Modules\Article\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PostsRequest extends FormRequest
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
            'name'              => 'required|max:191|unique:posts,name,'.$this->post,
            'slug'              => 'nullable|max:191',
            'intro'             => 'required',
            'content'           => 'required',
            'category_id'       => 'required|numeric',
            'banner'            => 'required|max:191',
            'status'            => 'required',
        ];
    }
}
