<?php
namespace App\Http\Requests\Api\Postgres\Admin\Collection;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Request;
use App\Http\Requests\BaseRequest;

class CreateMediaRequest extends Request
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
            'owner_id' => 'integer|required|exists:owners,id',
            'files' => 'required|array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'owner_id.integer' => __('notification.api-form-integer'),
            'owner_id.required' => __('notification.api-form-required'),
            'owner_id.exists' => __('notification.api-form-not-exists'),


            'files.required' => __('notification.api-form-required'),
            'files.array' => __('notification.api-form-array'),
        ];
    }
}
