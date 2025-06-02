<?php

namespace App\Http\Controllers\Api\Vouchers;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\Api\Vouchers\VoucherCollection;
use Illuminate\Http\Request;

class ShowVouchersController extends BaseController
{
    public function __invoke(Request $request)
    {
        $vouchers = auth()->user()
            ->vouchers()
            ->with('user')
            ->get();

        return (new VoucherCollection($vouchers))
            ->setMessage(__('shared.common.success'));
    }
}
