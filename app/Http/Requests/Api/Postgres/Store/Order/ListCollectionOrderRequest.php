<?php
namespace App\Http\Requests\Api\Postgres\Store\Order;

use App\Http\Requests\Api\Request;

class ListCollectionOrderRequest extends Request
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
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'store_id.required' => __('notification.api-form-required'),
            'store_id.integer' => __('notification.api-form-integer'),
            'store_id.exists' => __('notification.api-form-exists'),
        ];
    }
}
