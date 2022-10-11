<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    : array
    {
        return [
            'tag_id' => 'required|exists:tags,id',
            'title_ua' => 'required|string|max:100',
            'title_en' => 'required|string|max:100',
            'title_ru' => 'required|string|max:100',
            'description_ua' => 'required|string|max:100',
            'description_en' => 'required|string|max:100',
            'description_ru' => 'required|string|max:100',
            'content_ua' => 'required|string|max:100',
            'content_en' => 'required|string|max:100',
            'content_ru' => 'required|string|max:100',
        ];
    }
}
