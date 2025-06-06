<?php

namespace Tests\Feature\Api\Vouchers;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\Traits\Feature\AuthTest;

class DiscardVoucherTest extends VoucherTestCase
{
    use AuthTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->givenIHaveThisMethod('delete');
    }

    public function test_it_should_not_allow_route_access_unauthenticated_user(): void
    {
        $this->givenIHaveThisRoute(
            route('api.vouchers.discard', ['voucher_id' => 1])
        );

        $this->assertUnauthorizedAccess(method: $this->method, uri: $this->uri, headers: $this->headers);
    }

    public function test_it_should_not_discard_a_voucher_if_not_found(): void
    {
        $this->givenIHaveThisRoute(
            route('api.vouchers.discard', ['voucher_id' => 100])
        );

        Sanctum::actingAs($this->user);

        $this->whenICallThisEndpoint(
            method: $this->method,
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_NOT_FOUND);
        $this->thenIExpectAResponseStructure([
            'error_code',
            'message',
            'errors',
        ]);
    }

    public function test_it_should_not_discard_a_voucher_from_other_user(): void
    {
        $user = User::factory()->create();

        $voucher = Voucher::factory()
            ->create(
                attributes: ['user_id' => $user->id],
            );

        $this->givenIHaveThisRoute(
            route('api.vouchers.discard', ['voucher_id' => $voucher->id])
        );

        Sanctum::actingAs($this->user);

        $this->whenICallThisEndpoint(
            method: $this->method,
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_FORBIDDEN);
        $this->thenIExpectAResponseStructure([
            'error_code',
            'message',
            'errors',
        ]);
    }

    public function test_it_should_discard_a_voucher(): void
    {
        $voucher = Voucher::factory()
            ->create(
                attributes: ['user_id' => $this->user->id],
            );

        $this->givenIHaveThisRoute(
            route('api.vouchers.discard', ['voucher_id' => $voucher->id])
        );

        Sanctum::actingAs($this->user);

        $this->whenICallThisEndpoint(
            method: $this->method,
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_NO_CONTENT);
        $this->thenIExpectDatabaseMissing('vouchers', ['voucher_id' => $voucher->id, 'user_id' => $this->user->id]);
    }
}
