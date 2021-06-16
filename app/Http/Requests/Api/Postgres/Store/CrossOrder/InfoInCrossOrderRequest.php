<?php
namespace App\Http\Requests\Api\Postgres\Store\CrossOrder;

use App\Http\Requests\Api\Request;

class InfoInCrossOrderRequest extends Request
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
            'branch_id' => 'integer|exists:branches,id',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'order_id.required' => __('notification.api-form-required'),
            'order_id.integer' => __('notification.api-form-integer'),
            'order_id.exists' => __('notification.api-form-exists'),

            'branch_id.integer' => __('notification.api-form-integer'),
            'branch_id.exists' => __('notification.api-form-exists'),
        ];
    }
}
