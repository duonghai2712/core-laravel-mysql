<?php
namespace App\Http\Requests\Api\Postgres\Admin\Store;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;
use App\Http\Requests\BaseRequest;

class UpdateStoreRequest extends Request
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
        $accountInfo = $this->get('accountInfo');
        $id = ($this->method() == 'POST') ? $this->get('id') : 0;

        $rules = [
            'id' => 'required|integer',
            'name'          => 'required|string|unique:stores,name,' . $id . ',id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'slug'          => 'required|string|unique:stores,slug,' . $id . ',id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'email'         => 'required|email',
            'username'         => 'required|string',
            'password'          => 'min:6',
            'phone_number'      => 'required|string|exists:store_accounts,phone_number',
            'representative' => 'required|string',
            'district_id'      => 'required|exists:districts,id',
            'province_id'      => 'required|exists:provinces,id',
            'is_active'      => 'integer',
            'role'        => 'required|integer',
            'brands'    => 'array'
        ];

        $hasFile = BaseRequest::hasFile('profile_store_image');

        if($hasFile){
            $rules['profile_store_image'] = 'image|max:5120';
        }

        return $rules;
    }

    public function messages()
    {
        return [

            'id.required' => __('notification.api-form-required'),
            'id.integer' => __('notification.api-form-integer'),

            'name.required' => __('notification.api-form-required'),
            'name.string' => __('notification.api-form-string'),
            'name.unique' => __('notification.api-form-unique'),

            'slug.required' => __('notification.api-form-required'),
            'slug.string' => __('notification.api-form-string'),
            'slug.unique' => __('notification.api-form-unique'),

            'password.min' => __('notification.api-form-min'),

            'representative.required' => __('notification.api-form-required'),
            'representative.string' => __('notification.api-form-string'),

            'email.required' => __('notification.api-form-required'),
            'email.email' => __('notification.api-form-email'),

            'username.required' => __('notification.api-form-required'),
            'username.string' => __('notification.api-form-string'),

            'role.integer' => __('notification.api-form-integer'),
            'role.required' => __('notification.api-form-required'),

            'phone_number.required' => __('notification.api-form-required'),
            'phone_number.string' => __('notification.api-form-string'),
            'phone_number.exists' => __('notification.api-form-not-exists'),

            'is_active.integer' => __('notification.api-form-integer'),

            'profile_store_image.image' => __('notification.api-form-must-image'),
            'profile_store_image.max' => __('notification.api-form-max'),

            'brands.array' => __('notification.api-form-array'),

            'district_id.exists' => __('notification.api-form-not-exists'),
            'district_id.required' => __('notification.api-form-required'),

            'province_id.exists' => __('notification.api-form-not-exists'),
            'province_id.required' => __('notification.api-form-required'),

        ];
    }
}
