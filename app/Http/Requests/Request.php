<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class Request extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->messages();
        if (!empty($errors)){
            $errors = $errors->toArray();

            if (isset($errors['slug'])){
                $errors['name'] = $errors['slug'];
                unset($errors['slug']);
            }
        }

        $response = ['error_code' => STATUS_API_INPUT_VALIDATOR, 'notification'=>__('notification.validator-data'), 'field' => $errors];
        throw new HttpResponseException(response()->json($response));
    }
}
