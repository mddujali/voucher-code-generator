<?php

namespace App\Http\Requests\Api;

use App\Support\Traits\Http\Templates\Requests\Api\ResponseTemplate;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

abstract class BaseRequest extends FormRequest
{
    use ResponseTemplate;

    public function failedAuthorization(): void
    {
        $status = Response::HTTP_UNAUTHORIZED;
        $statusText = Response::$statusTexts[$status];
        $errorCode = studly($statusText);
        $message = period_at_the_end($statusText);
        $errors = [];

        throw new HttpResponseException(
            $this->errorResponse(
                status: $status,
                errorCode: $errorCode,
                message: $message,
                errors: $errors
            )
        );
    }

    public function failedValidation(Validator $validator): void
    {
        $status = Response::HTTP_UNPROCESSABLE_ENTITY;
        $errorCode = 'ValidationFailed';
        $message = 'The given data was invalid.';
        $errors = $validator->errors()->toArray();

        throw new HttpResponseException(
            $this->errorResponse(
                status: $status,
                errorCode: $errorCode,
                message: $message,
                errors: $errors
            )
        );
    }
}
