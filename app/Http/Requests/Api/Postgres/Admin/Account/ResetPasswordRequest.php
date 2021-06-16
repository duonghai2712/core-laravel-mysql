<?php
namespace App\Http\Requests\Api\Postgres\Admin\Account;

use App\Http\Requests\Api\Request;

class ResetPasswordRequest extends Request
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
            'email' => 'required|email|exists:accounts,email',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'email.required' => __('notification.api-form-required'),
            'email.email' => __('notification.api-form-email'),
            'email.exists' => __('notification.api-email-not-found'),
        ];
    }
}
