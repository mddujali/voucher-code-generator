<?php

namespace Tests\Feature\Api\Auth;

use Generator;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Feature\Api\BaseTestCase;

class LoginTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->givenIHaveThisRoute(route('api.auth.login'));
    }

    #[DataProvider('invalidFieldsDataProvider')]
    public function test_it_should_not_login_a_user_with_invalid_fields($data): void
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

    public function test_it_should_not_login_a_user_with_invalid_credentials(): void
    {
        $data = [
            'email' => 'unknown@example.com',
            'password' => 'invalidpassword',
        ];

        $this->whenICallThisEndpoint(
            method: 'post',
            uri: $this->uri,
            data: $data,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_UNAUTHORIZED);
        $this->thenIExpectAResponseStructure([
            'error_code',
            'message',
            'errors',
        ]);
    }

    public function test_it_should_login_a_user(): void
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $this->whenICallThisEndpoint(
            method: 'post',
            uri: $this->uri,
            data: $data,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_OK);
        $this->thenIExpectAResponseStructure([
            'message',
            'data' => [
                'token_type',
                'access_token',
            ],
        ]);
    }

    public static function invalidFieldsDataProvider(): Generator
    {
        yield 'Missing Fields' => [
            [],
        ];

        yield 'Empty Fields' => [
            [
                'email' => '',
                'password' => '',
            ],
        ];

        yield 'Invalid Email Field' => [
            [
                'email' => 'example.com',
                'password' => 'password',
            ],
        ];

        yield 'Invalid Password Field' => [
            [
                'email' => 'test@example.com',
                'password' => '',
            ],
        ];
    }
}
