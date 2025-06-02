<?php

namespace App\Http\Controllers\Api\Vouchers;

use App\Actions\GenerateVoucherAction;
use App\Exceptions\VoucherLimitException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\Api\Vouchers\VoucherResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenerateVoucherController extends BaseController
{
    public function __invoke(Request $request, GenerateVoucherAction $action)
    {
        try {
            $voucher = $action->execute($request->user());
        } catch (Exception $exception) {
            if ($exception instanceof VoucherLimitException) {
                return $this->errorResponse(
                    status: Response::HTTP_BAD_REQUEST,
                    message: $exception->getMessage()
                );
            }

            return $this->errorResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $voucher->load('user');

        return (new VoucherResource($voucher))
            ->setMessage('Voucher code generated.');
    }
}
