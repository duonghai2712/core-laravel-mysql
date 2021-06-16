<?php
namespace App\Http\Requests\Api\Postgres\Admin\Store;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;
use App\Http\Requests\BaseRequest;

class CreateStoreRequest extends Request
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

        $rules = [
            'name'          => 'required|string|unique:stores,name,NULL,id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'slug'          => 'required|string|unique:stores,slug,NULL,id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'email'         => 'required|email|unique:store_accounts,email,NULL,id,deleted_at,NULL',
            'username'         => 'required|string|unique:store_accounts,username,NULL,id,deleted_at,NULL',
            'password'      => 'required|min:6',
            'phone_number'      => 'required|string|unique:store_accounts,phone_number,NULL,id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
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

            'name.required' => __('notification.api-form-required'),
            'name.string' => __('notification.api-form-string'),
            'name.unique' => __('notification.api-form-unique'),

            'slug.required' => __('notification.api-form-required'),
            'slug.string' => __('notification.api-form-string'),
            'slug.unique' => __('notification.api-form-unique'),

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

            'role.integer' => __('notification.api-form-integer'),
            'role.required' => __('notification.api-form-required'),

            'phone_number.required' => __('notification.api-form-required'),
            'phone_number.string' => __('notification.api-form-string'),
            'phone_number.unique' => __('notification.api-form-unique'),

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
