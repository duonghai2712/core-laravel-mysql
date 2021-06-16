<?php

namespace App\Http\Requests\Api\Postgres\Admin\Order;

use App\Http\Requests\BaseRequest;

class ListCollectionPaginationRequest extends BaseRequest
{
    public $offset = 0;

    public $limit = 20;
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
            'order_branch_id' => 'required|integer|exists:order_branches,id',
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

            'order_branch_id.required' => __('notification.api-form-required'),
            'order_branch_id.integer' => __('notification.api-form-integer'),
            'order_branch_id.exists' => __('notification.api-form-exists'),
        ];
    }

    /**
     * @return int
     */
    public function offset()
    {
        $page = $this->get('page', 1);

        $this->offset = ( $page - 1 ) * $this->limit;
        return $this->offset;
    }

    /**
     * @param int $default
     *
     * @return int
     */
    public function limit($default = 20)
    {
        $this->limit = $this->get('limit',$default);

        return $this->limit;
    }

    public function order($default = 'id')
    {
        $order = $this->get('order', $default);

        return $order;
    }

    public function direction($default = 'desc')
    {
        $direction = strtolower($this->get('direction', $default));
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        return $direction;
    }
}