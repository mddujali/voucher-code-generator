<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\BaseTestCase;

class LogoutTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->givenIHaveThisRoute(route('api.auth.logout'));
    }

    public function test_it_should_not_logout_unauthenticated_user(): void
    {
        $this->whenICallThisEndpoint(
            method: 'post',
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_UNAUTHORIZED);
        $this->thenIExpectAResponseStructure([
            'error_code',
            'message',
            'errors',
        ]);
    }

    public function test_it_should_logout_authenticated_user(): void
    {
        Sanctum::actingAs(
            User::query()
                ->where('email', 'test@example.com')
                ->first()
        );

        $this->whenICallThisEndpoint(
            method: 'post',
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_OK);
        $this->thenIExpectAResponseStructure([
            'message',
            'data',
        ]);
    }
}
