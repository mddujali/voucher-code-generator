<?php

namespace Tests\Feature\Api\Profile;

use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\BaseTestCase;

class CurrentUserTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->givenIHaveThisRoute(route('api.profile.current-user'));
    }

    public function test_it_should_not_get_current_user(): void
    {
        $this->whenICallThisEndpoint(
            method: 'get',
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_should_get_current_user(): void
    {
        Sanctum::actingAs(
            User::query()
                ->where('email', 'test@example.com')
                ->first()
        );

        $this->whenICallThisEndpoint(
            method: 'get',
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_OK);
    }
}
