<?php
namespace App\Http\Requests\Api\Postgres\Store\Order;

use App\Http\Requests\Api\Request;

class SaveOrderRequest extends Request
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
            'payment' => 'required',
            'ids_device' => 'array',
            'timeframes' => 'array',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'time_book' =>  'required|integer',
            'type_booking' =>'required|integer'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'payment.required' => __('notification.api-form-required'),

            'start_date.required' => __('notification.api-form-required'),

            'end_date.required' => __('notification.api-form-required'),

            'start_time.required' => __('notification.api-form-required'),

            'end_time.required' => __('notification.api-form-required'),

            'ids_device.array' => __('notification.api-form-array'),

            'timeframes.array' => __('notification.api-form-array'),

            'time_book.required' => __('notification.api-form-required'),
            'time_book.integer' => __('notification.api-form-integer'),

            'type_booking.required' => __('notification.api-form-required'),
            'type_booking.integer' => __('notification.api-form-integer'),
        ];
    }
}
