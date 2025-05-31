<?php

namespace Tests\Unit\Models;

use App\Models\Voucher;
use Tests\Unit\BaseTestCase;

class VoucherTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = app(Voucher::class);

        $this->table = 'vouchers';

        $this->columns = [
            'id',
            'user_id',
            'code',
            'created_at',
            'updated_at',
        ];
    }
}
