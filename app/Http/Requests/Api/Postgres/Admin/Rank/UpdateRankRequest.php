<?php
namespace App\Http\Requests\Api\Postgres\Admin\Rank;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class UpdateRankRequest extends Request
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
            'id' => 'required|integer|exists:ranks,id',
            'name' => 'required|string|unique:ranks,name,' . $id . ',id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'slug' => 'required|string|unique:ranks,slug,' . $id . ',id,project_id,' . $accountInfo['project_id'] . ',deleted_at,NULL',
            'coefficient' => 'required|integer',
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

            'coefficient.required' => __('notification.api-form-required'),
            'coefficient.integer' => __('notification.api-form-integer'),


        ];
    }
}
