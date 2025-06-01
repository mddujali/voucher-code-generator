<?php

namespace App\Actions;

use App\Exceptions\VoucherLimitException;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Str;

class GenerateVoucherAction
{
    public function execute(User $user): Voucher
    {
        if ($user->vouchers()->count() >= config('voucher.limit')) {
            throw new VoucherLimitException('Voucher limit reached.');
        }

        return $user->vouchers()
            ->create([
                'user_id' => $user->id,
                'code' => Str::random(config('voucher.length'))
            ]);
    }
}
