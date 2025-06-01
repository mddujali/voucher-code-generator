<?php

namespace Tests\Feature\Api\Vouchers;

use App\Models\User;
use Tests\Feature\Api\BaseTestCase;

abstract class VoucherTestCase extends BaseTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()
            ->where('email', 'test@example.com')
            ->first();
    }
}
