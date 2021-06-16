<?php
namespace App\Http\Requests\Api\Postgres\Store\Collection;

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
            'files' => 'required|array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'files.required' => __('notification.api-form-required'),
            'files.array' => __('notification.api-form-array'),
        ];
    }
}
