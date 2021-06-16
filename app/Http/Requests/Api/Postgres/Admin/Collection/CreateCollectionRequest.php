<?php
namespace App\Http\Requests\Api\Postgres\Admin\Collection;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;
use App\Http\Requests\BaseRequest;

class CreateCollectionRequest extends Request
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
            'customer_account_id' => 'integer|required|exists:customer_accounts,id',
            'name' => 'required|string',
            'files' => 'required|array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'id.integer' => __('notification.api-form-integer'),
            'id.required' => __('notification.api-form-required'),
            'id.exists' => __('notification.api-form-not-exists'),

            'name.required' => __('notification.api-form-required'),
            'name.string' => __('notification.api-form-string'),

            'files.required' => __('notification.api-form-required'),
            'files.array' => __('notification.api-form-array'),
        ];
    }
}
