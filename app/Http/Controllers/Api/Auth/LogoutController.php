<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LogoutController extends BaseController
{
    public function __invoke(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->user()
                ->currentAccessToken()
                ->delete();

            DB::commit();
        } catch (Exception) {
            DB::rollBack();

            return $this->errorResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(message: 'User logged out successfully');
    }
}
