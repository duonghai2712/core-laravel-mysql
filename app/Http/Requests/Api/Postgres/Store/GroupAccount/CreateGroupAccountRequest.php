<?php
namespace App\Http\Requests\Api\Postgres\Store\GroupAccount;

use App\Http\Requests\Api\Request;

class CreateGroupAccountRequest extends Request
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
            'name' => 'required|string|unique:group_store_accounts,name,NULL,id,store_id,' . $storeAccountInfo['store_id'] . ',deleted_at,NULL',
            'slug' => 'required|string|unique:group_store_accounts,slug,NULL,id,store_id,' . $storeAccountInfo['store_id'] . ',deleted_at,NULL',
            'permissions' => 'array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => __('notification.api-form-required'),
            'name.string' => __('notification.api-form-string'),
            'name.unique' => __('notification.api-form-unique'),

            'slug.required' => __('notification.api-form-required'),
            'slug.string' => __('notification.api-form-string'),
            'slug.unique' => __('notification.api-form-unique'),

            'permissions.array' => __('notification.api-form-array'),
        ];
    }
}
