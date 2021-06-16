<?php
namespace App\Http\Requests\Api\Postgres\Store\Order;

use App\Http\Requests\Api\Request;

class InfoInOrderRequest extends Request
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
            'order_store_id' => 'required|integer|exists:order_stores,id',
            'order_branch_id' => 'integer|exists:order_branches,id',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'order_id.required' => __('notification.api-form-required'),
            'order_id.integer' => __('notification.api-form-integer'),
            'order_id.exists' => __('notification.api-form-exists'),

            'order_store_id.required' => __('notification.api-form-required'),
            'order_store_id.integer' => __('notification.api-form-integer'),
            'order_store_id.exists' => __('notification.api-form-exists'),

            'order_branch_id.integer' => __('notification.api-form-integer'),
            'order_branch_id.exists' => __('notification.api-form-exists'),
        ];
    }
}
