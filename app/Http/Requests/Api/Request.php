<?php
namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Elibs\eResponse;

class Request extends BaseRequest
{
    /**
     * Get the failed validation response for the request.
     *
     * @params array $errors
     */
    public function response(array $errors)
    {
        $transformed = [];

        foreach ($errors as $field => $message) {
            $transformed[$field] = $message[0];
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.api.data-not-exits'), $transformed);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
