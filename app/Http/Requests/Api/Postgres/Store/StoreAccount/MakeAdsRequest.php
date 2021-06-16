<?php
namespace App\Http\Requests\Api\Postgres\Store\StoreAccount;

use App\Http\Requests\Api\Request;

class MakeAdsRequest extends Request
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
            'id' => 'integer|required|exists:store_accounts,id',
            'make_ads' => 'integer|required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'id.integer' => __('notification.api-form-integer'),
            'id.required' => __('notification.api-form-required'),
            'id.exists' => __('notification.api-form-not-exists'),

            'make_ads.integer' => __('notification.api-form-integer'),
            'make_ads.required' => __('notification.api-form-required'),
        ];
    }
}
