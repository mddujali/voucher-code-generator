<?php

namespace App\Http\Controllers\Api\Vouchers;

use App\Exceptions\Json\HttpJsonException;
use App\Http\Controllers\Api\BaseController;
use App\Models\Voucher;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiscardVoucherController extends BaseController
{
    public function __invoke(Request $request)
    {
        $voucher = Voucher::query()
            ->where('id', $request->route()->parameter('voucher_id'))
            ->first();

        if (blank($voucher)) {
            throw new HttpJsonException(Response::HTTP_NOT_FOUND, message: 'Voucher not found.');
        }

        if ($request->user()->id !== $voucher->user_id) {
            throw new HttpJsonException(Response::HTTP_FORBIDDEN, message: 'Unauthorized to discard voucher.');
        }

        try {
            $voucher->delete();
        } catch (Exception $exception) {
            if ($exception instanceof QueryException) {
                return $this->queryErrorResponse($exception);
            }

            if ($exception instanceof HttpJsonException) {
                return $this->httpErrorResponse($exception);
            }

            return $this->errorResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->noContentResponse();
    }
}
