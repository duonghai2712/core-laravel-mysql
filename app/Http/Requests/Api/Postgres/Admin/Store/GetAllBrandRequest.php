<?php
namespace App\Http\Requests\Api\Postgres\Admin\Store;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;
use App\Http\Requests\BaseRequest;

class GetAllBrandRequest extends Request
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
            'store_id' => 'integer|required|exists:stores,id',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'store_id.integer' => __('notification.api-form-integer'),
            'store_id.required' => __('notification.api-form-required'),
            'store_id.exists' => __('notification.api-form-not-exists'),
        ];
    }
}
