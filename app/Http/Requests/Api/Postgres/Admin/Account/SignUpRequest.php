<?php
namespace App\Http\Requests\Api\Postgres\Admin\Account;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class SignUpRequest extends Request
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
            'name' => 'required|string',
            'email' => 'required|email|unique:accounts,email,NULL,id,deleted_at,NULL',
            'username' => 'required|string|unique:accounts,username,NULL,id,deleted_at,NULL',
            'password' =>'required|min:6',
            'project_name' => 'required|string|unique:projects,name,NULL,id,deleted_at,NULL',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => __('notification.api-form-required'),
            'name.string' => __('notification.api-form-string'),

            'email.required' => __('notification.api-form-required'),
            'email.string' => __('notification.api-form-string'),
            'email.unique' => __('notification.api-form-unique'),

            'username.required' => __('notification.api-form-required'),
            'username.email' => __('notification.api-form-email'),
            'username.unique' => __('notification.api-form-unique'),

            'password.required' => __('notification.api-form-required'),
            'password.min' => __('notification.api-form-min'),

            'project_name.required' => __('notification.api-form-required'),
            'project_name.string' => __('notification.api-form-string'),
            'project_name.unique' => __('notification.api-form-unique'),
        ];
    }
}
