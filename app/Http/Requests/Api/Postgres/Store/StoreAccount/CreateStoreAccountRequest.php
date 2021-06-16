<?php
namespace App\Http\Requests\Api\Postgres\Store\StoreAccount;

use App\Http\Requests\Api\Request;

class CreateStoreAccountRequest extends Request
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
        $storeAccountInfo = $this->get('storeAccountInfo');
        $rules = [
            'username'         => 'required|string|unique:store_accounts,username,NULL,id,store_id,' . $storeAccountInfo['store_id'] . ',deleted_at,NULL',
            'representative' => 'required|string',
            'email'         => 'required|email|unique:store_accounts,email,NULL,id,deleted_at,NULL',
            'phone_number'      => 'required|string|unique:store_accounts,phone_number,NULL,id,store_id,' . $storeAccountInfo['store_id'] . ',deleted_at,NULL',
            'group_store_account_id' => 'required|exists:group_store_accounts,id',
            'branch_id' => 'required|exists:branches,id',
            'password'      => 'required|min:6',
            'is_active'      => 'integer',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'email.required' => __('notification.api-form-required'),
            'email.email' => __('notification.api-form-email'),
            'email.unique' => __('notification.api-form-unique'),

            'username.required' => __('notification.api-form-required'),
            'username.string' => __('notification.api-form-string'),
            'username.unique' => __('notification.api-form-unique'),

            'representative.required' => __('notification.api-form-required'),
            'representative.string' => __('notification.api-form-string'),

            'password.required' => __('notification.api-form-required'),
            'password.min' => __('notification.api-form-min'),

            'phone_number.required' => __('notification.api-form-required'),
            'phone_number.string' => __('notification.api-form-string'),
            'phone_number.unique' => __('notification.api-form-unique'),

            'is_active.integer' => __('notification.api-form-integer'),

            'branch_id.exists' => __('notification.api-form-not-exists'),
            'branch_id.required' => __('notification.api-form-required'),

            'group_store_account_id.exists' => __('notification.api-form-not-exists'),
            'group_store_account_id.required' => __('notification.api-form-required'),
        ];
    }
}
