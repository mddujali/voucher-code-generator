<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\GenerateVoucherAction;
use App\Exceptions\VoucherLimitException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Mail\RegisteredUserEmail;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends BaseController
{
    public function __invoke(RegisterRequest $request, GenerateVoucherAction $action)
    {
        DB::beginTransaction();

        try {
            $user = User::query()
                ->create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

            $voucher = $action->execute($user);

            Mail::to($user->email)->send(new RegisteredUserEmail($user, $voucher));

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            if ($exception instanceof VoucherLimitException) {
                return $this->errorResponse(
                    status: Response::HTTP_BAD_REQUEST,
                    message: $exception->getMessage()
                );
            }

            if ($exception instanceof QueryException) {
                return $this->queryErrorResponse($exception);
            }

            return $this->errorResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(
            status: Response::HTTP_CREATED,
            message: 'User registered successfully.',
            data: [
                'token_type' => PersonalAccessToken::TOKEN_TYPE,
                'access_token' => $user->createToken($user->email)
                    ->plainTextToken,
            ]
        );
    }
}
