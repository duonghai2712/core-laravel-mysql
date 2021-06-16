<?php
namespace App\Http\Requests\Api\Postgres\Admin\Store;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;
use App\Http\Requests\BaseRequest;

class ChangeStatusStoreRequest extends Request
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
            'id' => 'required|integer|exists:stores,id',
            'is_active' => 'required|integer',
        ];


        return $rules;
    }

    public function messages()
    {
        return [

            'id.required' => __('notification.api-form-required'),
            'id.integer' => __('notification.api-form-integer'),
            'id.exists' => __('notification.api-form-not-exists'),

            'is_active.required' => __('notification.api-form-required'),
            'is_active.integer' => __('notification.api-form-integer'),

        ];
    }
}
