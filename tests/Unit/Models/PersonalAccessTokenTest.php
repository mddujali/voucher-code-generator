<?php

namespace Tests\Unit\Models;

use App\Models\PersonalAccessToken;
use Tests\Unit\BaseTestCase;

class PersonalAccessTokenTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = app(PersonalAccessToken::class);

        $this->table = 'personal_access_tokens';

        $this->columns = [
            'id',
            'tokenable_type',
            'tokenable_id',
            'name',
            'token',
            'abilities',
            'last_used_at',
            'expires_at',
            'created_at',
            'updated_at',
        ];
    }
}
