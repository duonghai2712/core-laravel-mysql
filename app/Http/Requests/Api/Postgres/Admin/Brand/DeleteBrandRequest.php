<?php
namespace App\Http\Requests\Api\Postgres\Admin\Brand;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class DeleteBrandRequest extends Request
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
            'ids' => 'required|array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [

            'ids.array' => __('notification.api-form-array'),
            'ids.required' => __('notification.api-form-required'),


        ];
    }
}
