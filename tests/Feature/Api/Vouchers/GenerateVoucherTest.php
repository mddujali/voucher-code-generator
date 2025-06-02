<?php

namespace Tests\Feature\Api\Vouchers;

use App\Models\Voucher;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\Traits\Feature\AuthTest;

class GenerateVoucherTest extends VoucherTestCase
{
    use AuthTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->givenIHaveThisMethod('post');
        $this->givenIHaveThisRoute(route('api.vouchers.generate'));
    }

    public function test_it_should_not_allow_route_access_unauthenticated_user(): void
    {
        $this->assertUnauthorizedAccess(method: $this->method, uri: $this->uri, headers: $this->headers);
    }

    public function test_it_should_not_generate_a_voucher(): void
    {
        Voucher::factory(10)
            ->create(
                attributes: ['user_id' => $this->user->id],
            );

        Sanctum::actingAs($this->user);

        $this->whenICallThisEndpoint(
            method: $this->method,
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_BAD_REQUEST);
        $this->thenIExpectAResponseStructure([
            'error_code',
            'message',
            'errors',
        ]);
    }

    public function test_it_should_generate_a_voucher(): void
    {
        Sanctum::actingAs($this->user);

        $this->whenICallThisEndpoint(
            method: $this->method,
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_CREATED);
        $this->thenIExpectAResponseStructure([
            'message',
            'data' => [
                'id',
                'user',
                'code',
                'created_at',
                'updated_at',
            ],
        ]);
        $this->thenIExpectInDatabase('vouchers', ['user_id' => $this->user->id]);
    }
}
