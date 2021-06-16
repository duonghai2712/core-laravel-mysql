<?php
namespace App\Http\Requests\Api\Postgres\Store\Account;

use App\Http\Requests\Api\Request;

class ResetPasswordLastStepRequest extends Request
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
            'password' =>'min:6',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'token.required' => __('notification.api-form-required'),
            'password.min' => __('notification.api-form-min'),
        ];
    }
}
