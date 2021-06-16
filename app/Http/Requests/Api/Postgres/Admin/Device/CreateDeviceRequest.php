<?php
namespace App\Http\Requests\Api\Postgres\Admin\Device;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class CreateDeviceRequest extends Request
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
            'name' => 'required|string|unique:devices,name,NULL,id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'slug' => 'required|string|unique:devices,slug,NULL,id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'own' => 'required|integer',
            'store_id' => 'required|exists:stores,id',
            'branch_id' => 'required|exists:branches,id',
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

            'own.required' => __('notification.api-form-not-required'),
            'own.integer' => __('notification.api-form-integer'),

            'store_id.exists' => __('notification.api-form-not-exists'),
            'store_id.required' => __('notification.api-form-required'),

            'branch_id.exists' => __('notification.api-form-not-exists'),
            'branch_id.required' => __('notification.api-form-required'),


        ];
    }
}
