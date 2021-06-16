<?php
namespace App\Http\Requests\Api\Postgres\App;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class SignInRequest extends Request
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
            'device_code'=>'required',
            'active_code'=>'required|integer',
            'model'=>'required',
            'width'=>'required',
            'height'=>'required',
            'size'=>'required',
            'os'=>'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'active_code.integer' => __('notification.api-form-integer'),
            'active_code.required' => __('notification.api-form-required'),

            'device_code.required' => __('notification.api-form-required'),

            'model.required' => __('notification.api-form-required'),

            'width.required' => __('notification.api-form-required'),

            'height.required' => __('notification.api-form-required'),

            'size.required' => __('notification.api-form-required'),

            'os.required' => __('notification.api-form-required'),
        ];
    }
}
