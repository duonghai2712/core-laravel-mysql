<?php
namespace App\Http\Requests\Api\Postgres\Admin\Order;

use App\Http\Requests\Api\Request;

class AcceptRequestRequest extends Request
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
            'order_id' => 'required|integer|exists:orders,id',
            'type' => 'required|integer',
            'reason' => 'string',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'order_id.required' => __('notification.api-form-required'),
            'order_id.integer' => __('notification.api-form-integer'),
            'order_id.exists' => __('notification.api-form-exists'),

            'type.required' => __('notification.api-form-required'),
            'type.integer' => __('notification.api-form-integer'),

            'reason.string' => __('notification.api-form-string'),
        ];
    }
}
