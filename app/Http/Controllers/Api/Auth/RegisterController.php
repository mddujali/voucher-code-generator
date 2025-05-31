<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseController
{
    public function __invoke(RegisterRequest $request)
    {
        $user = User::query()
            ->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

        return $this->successResponse(
            status: Response::HTTP_CREATED,
            message: 'User registered successfully.',
            data: [
                'token_type' => 'Bearer',
                'access_token' => $user->createToken($user->email)
                    ->plainTextToken,
            ]
        );
    }
}
