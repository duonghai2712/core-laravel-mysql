<?php
namespace App\Http\Requests\Api\Postgres\Admin\Device;

use App\Http\Requests\Api\Request;

class UpdateDeviceRequest extends Request
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
            'id' => 'integer|required|exists:devices,id',
            'name' => 'required|string|unique:devices,name,' . $id . ',id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'slug' => 'required|string|unique:devices,slug,' . $id . ',id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'own' => 'required|integer',
            'store_id' => 'required|exists:stores,id',
            'branch_id' => 'required|exists:branches,id',
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

            'own.required' => __('notification.api-form-not-required'),
            'own.integer' => __('notification.api-form-integer'),

            'store_id.exists' => __('notification.api-form-not-exists'),
            'store_id.required' => __('notification.api-form-required'),

            'branch_id.exists' => __('notification.api-form-not-exists'),
            'branch_id.required' => __('notification.api-form-required'),


        ];
    }
}
