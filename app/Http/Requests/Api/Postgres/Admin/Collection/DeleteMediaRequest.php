<?php

namespace App\Http\Requests\Api\Postgres\Admin\Collection;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class DeleteMediaRequest extends Request
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

        $rules = [
            'ids' => 'array|required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'ids.array' => __('notification.api-form-array'),
            'ids.required' => __('notification.api-form-required'),
        ];
    }
}
