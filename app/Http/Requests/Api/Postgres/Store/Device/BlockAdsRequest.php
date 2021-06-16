<?php
namespace App\Http\Requests\Api\Postgres\Store\Device;

use App\Http\Requests\Api\Request;

class BlockAdsRequest extends Request
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

        $rules = [
            'id' => 'integer|required|exists:devices,id',
            'time' => 'required|integer|max:180'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'id.integer' => __('notification.api-form-integer'),
            'id.required' => __('notification.api-form-required'),
            'id.exists' => __('notification.api-form-not-exists'),

            'time.required' => __('notification.api-form-required'),
            'time.integer' => __('notification.api-form-integer'),
            'time.max' => __('notification.api-form-max'),
        ];
    }
}
