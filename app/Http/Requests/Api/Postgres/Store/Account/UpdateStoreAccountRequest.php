<?php
namespace App\Http\Requests\Api\Postgres\Store\Account;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;
use App\Http\Requests\BaseRequest;

class UpdateStoreAccountRequest extends Request
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
        $id = ($this->method() == 'POST') ? $this->get('id') : 0;

        $rules = [
            'id' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|string|unique:store_accounts,email,' . $id . ',id,deleted_at,NULL',
            'password' =>'min:6',
            'phone_number' => 'string',
        ];

        $hasFile = BaseRequest::hasFile('profile_image');

        if($hasFile){
            $rules['profile_image'] = 'image|max:5120';
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

            'email.required' => __('notification.api-form-required'),
            'email.string' => __('notification.api-form-string'),
            'email.unique' => __('notification.api-form-unique'),

            'password.min' => __('notification.api-form-min'),

            'phone_number.string' => __('notification.api-form-string'),

            'profile_image.image' => __('notification.api-form-must-image'),
            'profile_image.max' => __('notification.api-form-max'),

        ];

    }
}
