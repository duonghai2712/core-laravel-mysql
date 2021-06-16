<?php
namespace App\Http\Requests\Api\Postgres\Admin\Account;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;
use App\Http\Requests\BaseRequest;

class UpdateAccountRequest extends Request
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
            'password' =>'min:6',
            'email' => 'required|string|unique:accounts,email,' . $id . ',id,deleted_at,NULL',
            'phone_number' => 'required|string',
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

            'password.min' => __('notification.api-form-min'),

            'name.required' => __('notification.api-form-required'),
            'name.string' => __('notification.api-form-string'),

            'email.required' => __('notification.api-form-required'),
            'email.string' => __('notification.api-form-string'),
            'email.unique' => __('notification.api-form-unique'),

            'phone_number.string' => __('notification.api-form-string'),
            'phone_number.required' => __('notification.api-form-required'),

            'profile_image.image' => __('notification.api-form-must-image'),
            'profile_image.max' => __('notification.api-form-max'),
        ];
    }
}
