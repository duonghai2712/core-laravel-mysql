<?php
namespace App\Http\Requests\Api\Postgres\App;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;

class CheckStatusCollectionDownloadRequest extends Request
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
            'data' => 'required|string',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'data.string' => __('notification.api-form-string'),
            'data.required' => __('notification.api-form-required'),
        ];
    }
}
