<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\BaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurrentUserController extends BaseController
{
    public function __invoke(Request $request)
    {
        try {
            $user = $request->user();
        } catch (Exception) {
            return $this->errorResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(data: ['user' => $user]);
    }
}
