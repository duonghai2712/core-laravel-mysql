<?php
namespace App\Http\Requests\Api\Postgres\Admin\Branch;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class UpdateBranchRequest extends Request
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
        $store_id = $this->get('store_id');
        $id = ($this->method() == 'POST') ? $this->get('id') : 0;

        $rules = [
            'id' => 'integer|required|exists:branches,id',
            'name' => 'required|string|unique:branches,name,' . $id . ',id,store_id,' . $store_id . ',project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'slug' => 'required|string|unique:branches,slug,' . $id . ',id,store_id,' . $store_id . ',project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'contact' => 'required|string',
            'phone_number' => 'string|required|unique:branches,phone_number,' . $id . ',id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'district_id' => 'required|exists:districts,id',
            'province_id' => 'required|exists:provinces,id',
            'store_id' => 'required|exists:stores,id',
            'rank_id' => 'required|exists:ranks,id',
            'brands' => 'array',
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

            'slug.required' => __('notification.api-form-required'),
            'slug.string' => __('notification.api-form-string'),
            'slug.unique' => __('notification.api-form-unique'),

            'contact.required' => __('notification.api-form-required'),
            'contact.string' => __('notification.api-form-string'),

            'phone_number.string' => __('notification.api-form-string'),
            'phone_number.required' => __('notification.api-form-required'),
            'phone_number.unique' => __('notification.api-form-unique'),

            'district_id.required' => __('notification.api-form-required'),
            'district_id.exists' => __('notification.api-form-not-exists'),

            'province_id.required' => __('notification.api-form-required'),
            'province_id.exists' => __('notification.api-form-not-exists'),

            'store_id.exists' => __('notification.api-form-not-exists'),
            'store_id.required' => __('notification.api-form-required'),

            'rank_id.exists' => __('notification.api-form-not-exists'),
            'rank_id.required' => __('notification.api-form-required'),

            'brands.array' => __('notification.api-form-array'),

        ];
    }
}
