<?php
namespace App\Http\Requests\Api\Postgres\Store\StoreInfo;

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
        $storeAccountInfo = $this->get('storeAccountInfo');
        $id = ($this->method() == 'POST') ? $this->get('id') : 0;

        $rules = [
            'id' => 'integer|required|exists:branches,id',
            'name' => 'required|string|unique:branches,name,' . $id . ',id,project_id,' . $storeAccountInfo['project_id'] . ',deleted_at,NULL',
            'contact' => 'required|string',
            'phone_number' => 'string|required',
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

            'contact.required' => __('notification.api-form-required'),
            'contact.string' => __('notification.api-form-string'),

            'phone_number.string' => __('notification.api-form-string'),
            'phone_number.required' => __('notification.api-form-required'),
        ];
    }
}
