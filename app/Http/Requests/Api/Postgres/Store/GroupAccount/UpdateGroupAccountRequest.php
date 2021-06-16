<?php
namespace App\Http\Requests\Api\Postgres\Store\GroupAccount;

use App\Http\Requests\Api\Request;

class UpdateGroupAccountRequest extends Request
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
        $id = ($this->method() == 'POST') ? $this->get('id') : 0;

        $rules = [
            'id' => 'integer|required|exists:group_store_accounts,id',
            'name' => 'required|string|unique:group_store_accounts,name,' . $id . ',id,store_id,' . $storeAccountInfo['store_id'] . ',deleted_at,NULL',
            'permissions' => 'array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'id.integer' => __('notification.api-form-integer'),
            'id.required' => __('notification.api-form-required'),
            'id.exists' => __('notification.api-form-not-exists'),

            'name.required' => __('notification.api-form-required'),
            'name.string' => __('notification.api-form-string'),
            'name.unique' => __('notification.api-form-unique'),

            'permissions.array' => __('notification.api-form-array'),
        ];
    }
}
