<?php
namespace App\Http\Requests\Api\Postgres\Admin\Brand;

use App\Http\Requests\Api\Request;

class AddSubBrandRequest extends Request
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
            'id' => 'required|integer|exists:brands,id',
            'subBrand' => 'array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [

            'id.required' => __('notification.api-form-required'),
            'id.integer' => __('notification.api-form-integer'),
            'id.exists' => __('notification.api-form-not-exists'),

            'subBrand.array' => __('notification.api-form-array'),

        ];
    }
}
