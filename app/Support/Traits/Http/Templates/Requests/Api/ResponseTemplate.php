<?php

namespace App\Support\Traits\Http\Templates\Requests\Api;

use App\Exceptions\Json\HttpJsonException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

trait ResponseTemplate
{
    public function successResponse(
        int $status = Response::HTTP_OK,
        string $message = '',
        array $data = [],
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        $data = [
            'message' => empty($message)
                ? __('shared.common.success')
                : period_at_the_end($message),
            'data' => $data,
        ];

        return response()->json($data, $status, $headers, $options);
    }

    public function noContentResponse(array $headers = []): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT, $headers);
    }

    public function errorResponse(
        int $status,
        string $errorCode = '',
        string $message = '',
        array $errors = [],
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return response()->json([
            'error_code' => empty($errorCode)
                ? studly(Response::$statusTexts[$status])
                : studly($errorCode),
            'message' => empty($message)
                ? __('shared.common.error')
                : period_at_the_end($message),
            'errors' => $errors,
        ], $status, $headers, $options);
    }

    public function httpErrorResponse(HttpJsonException $exception): JsonResponse
    {
        return $this->errorResponse(
            status: $exception->getStatus(),
            errorCode: $exception->getErrorCode(),
            message: $exception->getMessage(),
            errors: $exception->getErrors()
        );
    }

    public function queryErrorResponse(QueryException $exception): JsonResponse
    {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        $description = 'The server encountered an internal error or misconfiguration.';

        if (config('app.debug')) {
            [, , $description] = $exception->errorInfo;
        }

        return $this->errorResponse(
            status: $status,
            errorCode: studly(Response::$statusTexts[$status]),
            message: __('shared.query.' . $status),
            errors: [
                'server' => period_at_the_end($description),
            ]
        );
    }

    public function serverErrorResponse(Throwable $exception): JsonResponse
    {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;

        return $this->errorResponse(
            status: $status,
            errorCode: studly(Response::$statusTexts[$status]),
            message: __('shared.http.' . $status),
            errors: [
                'server' => period_at_the_end($exception->getMessage()),
            ]
        );
    }
}
