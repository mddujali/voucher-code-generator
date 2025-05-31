<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use Exception;
use Illuminate\Http\Response;

class LoginController extends BaseController
{
    public function __invoke(LoginRequest $request)
    {
        try {
            $data = $request->validated();

            if (!auth()->attempt($data)) {
                return $this->errorResponse(
                    status: Response::HTTP_UNAUTHORIZED,
                    message: 'Invalid credentials'
                );
            }
        } catch (Exception) {
            return $this->errorResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(
            data: [
                'token_type' => 'Bearer',
                'access_token' => $request->user()
                    ->createToken($data['email'])
                    ->plainTextToken,
            ]
        );
    }
}
