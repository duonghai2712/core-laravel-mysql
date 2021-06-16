<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class RequestSignIn extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = ['error_code' => STATUS_API_INPUT_VALIDATOR, 'notification'=>__('notification.sign-in-failed'), []];
        throw new HttpResponseException(response()->json($response));
    }
}
