<?php
namespace App\Http\Requests\Api\Postgres\Admin\Brand;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class CreateBrandRequest extends Request
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
        $accountInfo = $this->get('accountInfo');
        $rules = [
            'name' => 'required|string|unique:brands,name,NULL,id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'slug' => 'required|string|unique:brands,slug,NULL,id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'subBrand' => 'array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [

            'name.string' => __('notification.api-form-string'),
            'name.unique' => __('notification.api-form-brand-unique'),
            'name.required' => __('notification.api-form-required'),

            'slug.string' => __('notification.api-form-string'),
            'slug.unique' => __('notification.api-form-brand-unique'),
            'slug.required' => __('notification.api-form-required'),


            'subBrand.array' => __('notification.api-form-array'),


        ];
    }
}
