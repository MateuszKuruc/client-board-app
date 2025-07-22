<?php

namespace App\Http\Requests\Tags;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class SyncTagsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize()
    {
        // you can add your authorization logic here
        return true;
    }

    public function rules()
    {
        return [
            'tags'   => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ];
    }
}
