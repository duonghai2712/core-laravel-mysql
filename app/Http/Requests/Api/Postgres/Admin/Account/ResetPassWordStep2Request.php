<?php

namespace App\Http\Requests\Api\Postgres\Admin\Account;

use App\Http\Requests\Api\Request;

class ResetPassWordStep2Request extends Request
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
            'token' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'token.required' => __('notification.api-form-required'),
        ];
    }
}
