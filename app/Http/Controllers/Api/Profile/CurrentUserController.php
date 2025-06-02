<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\Api\Users\UserResource;
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

        return (new UserResource($user))
            ->setMessage(__('shared.common.success'));
    }
}
