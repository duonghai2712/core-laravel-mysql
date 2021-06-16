<?php
namespace App\Http\Requests\Api\Postgres\Store\Account;

use App\Http\Requests\RequestSignIn;

class SignInRequest extends RequestSignIn
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
            'username' => 'required',
            'password' =>'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'username.required' => __('notification.api-form-required'),

            'password.required' => __('notification.api-form-required'),
        ];
    }
}
