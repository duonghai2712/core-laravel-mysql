<?php
namespace App\Http\Requests\Api\Postgres\Admin\Brand;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class UpdateBrandRequest extends Request
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
        $id = ($this->method() == 'POST') ? $this->get('id') : 0;
        $accountInfo = $this->get('accountInfo');
        $rules = [
            'id' => 'required|integer|exists:brands,id',
            'name' => 'required|string|unique:brands,name,' . (int)$id . ',id,project_id,' . (int)$accountInfo['project_id'] . ',deleted_at,NULL',
            'slug' => 'required|string|unique:brands,slug,' . (int)$id . ',id,project_id,' . (int)$accountInfo['project_id'] . ',deleted_at,NULL',
            'idsDelSubBrand' => 'array',
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

            'name.string' => __('notification.api-form-string'),
            'name.unique' => __('notification.api-form-unique'),
            'name.required' => __('notification.api-form-required'),

            'slug.string' => __('notification.api-form-string'),
            'slug.unique' => __('notification.api-form-unique'),
            'slug.required' => __('notification.api-form-required'),

            'subBrand.array' => __('notification.api-form-array'),

            'idsDelSubBrand.array' => __('notification.api-form-array'),
        ];
    }
}
