<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Feature\Api\BaseTestCase;
use Generator;

class RegisterTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->givenIHaveThisRoute(route('api.auth.register'));
    }

    #[DataProvider('invalidFieldsDataProvider')]
    public function test_it_should_not_register_a_user_with_invalid_fields($data): void
    {
        $this->whenICallThisEndpoint(
            method: 'post',
            uri: $this->uri,
            data: $data,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->thenIExpectAResponseStructure([
            'error_code',
            'message',
            'errors',
        ]);
    }

    public function test_it_should_register_a_user(): void
    {
        $this->whenICallThisEndpoint(
            method: 'post',
            uri: $this->uri,
            data: [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ],
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_CREATED);
        $this->thenIExpectAResponseStructure([
            'message',
            'data' => [
                'token_type',
                'access_token',
            ],
        ]);
        $this->thenIExpectInDatabase('users', ['name' => 'John Doe', 'email' => 'john.doe@example.com']);
    }

    public static function invalidFieldsDataProvider(): Generator
    {
        yield 'Missing Fields' => [
            [],
        ];

        yield 'Empty Fields' => [
            [
                'name' => '',
                'email' => '',
                'password' => '',
                'password_confirmation' => '',
            ],
        ];

        yield 'Invalid Email Field' => [
            [
                'name' => 'John Doe',
                'email' => 'example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ],
        ];

        yield 'Invalid Password Field' => [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => '',
                'password_confirmation' => 'password',
            ],
        ];

        yield 'Unmatched Password Confirmation' => [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => 'password',
                'password_confirmation' => 'incorrectPassword',
            ],
        ];
    }
}
