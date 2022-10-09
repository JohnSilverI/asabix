<?php

namespace App\Http\Requests;

class TagUpdateRequest extends TagCreateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    : array
    {
        $rules = parent::rules();

        $rules['id'] = [
            'required',
            'integer',
        ];

        return $rules;
    }
}
