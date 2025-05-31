<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\Unit\BaseTestCase;

class UserTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = app(User::class);

        $this->table = 'users';

        $this->columns = [
            'id',
            'name',
            'email',
            'email_verified_at',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
        ];
    }
}
