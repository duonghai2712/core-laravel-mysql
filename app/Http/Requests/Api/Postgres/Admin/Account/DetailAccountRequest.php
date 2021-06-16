<?php
namespace App\Http\Requests\Api\Postgres\Admin\Account;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class DetailAccountRequest extends Request
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
            'id' => 'required|integer|exists:accounts,id',
        ];

        return $rules;
    }

    public function messages()
    {
        return [

            'id.required' => __('notification.api-form-required'),
            'id.integer' => __('notification.api-form-integer'),
            'id.exists' => __('notification.api-form-not-exists'),

        ];
    }
}
