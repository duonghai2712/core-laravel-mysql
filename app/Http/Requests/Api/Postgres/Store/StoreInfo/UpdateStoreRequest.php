<?php
namespace App\Http\Requests\Api\Postgres\Store\StoreInfo;

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
        $storeAccountInfo = $this->get('storeAccountInfo');
        $id = ($this->method() == 'POST') ? $this->get('id') : 0;

        $rules = [
            'id' => 'required|integer|exists:stores,id',
            'name'          => 'required|string|unique:stores,name,' . $id . ',id,project_id,' . $storeAccountInfo['project_id'] . ',deleted_at,NULL',
            'district_id'      => 'required|exists:districts,id',
            'province_id'      => 'required|exists:provinces,id',
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
            'id.exists' => __('notification.api-form-not-exists'),

            'name.required' => __('notification.api-form-required'),
            'name.string' => __('notification.api-form-string'),
            'name.unique' => __('notification.api-form-unique'),


            'district_id.exists' => __('notification.api-form-not-exists'),
            'district_id.required' => __('notification.api-form-required'),

            'province_id.exists' => __('notification.api-form-not-exists'),
            'province_id.required' => __('notification.api-form-required'),

        ];
    }
}
