<?php

namespace Tests\Feature\Api\Profile;

use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\BaseTestCase;
use Tests\Traits\Feature\AuthTest;

class CurrentUserTest extends BaseTestCase
{
    use AuthTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->givenIHaveThisMethod('get');
        $this->givenIHaveThisRoute(route('api.profile.current-user'));
    }

    public function test_it_should_not_allow_route_access_unauthenticated_user(): void
    {
        $this->assertUnauthorizedAccess(method: $this->method, uri: $this->uri, headers: $this->headers);
    }

    public function test_it_should_get_current_user(): void
    {
        Sanctum::actingAs(
            User::query()
                ->where('email', 'test@example.com')
                ->first()
        );

        $this->whenICallThisEndpoint(
            method: $this->method,
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_OK);
        $this->thenIExpectAResponseStructure([
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }
}
