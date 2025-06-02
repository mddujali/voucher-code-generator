<?php

namespace Tests\Feature\Api\Vouchers;

use App\Models\Voucher;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\Traits\Feature\AuthTest;

class ShowVouchersTest extends VoucherTestCase
{
    use AuthTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->givenIHaveThisMethod('get');
        $this->givenIHaveThisRoute(route('api.vouchers.list'));
    }

    public function test_it_should_not_allow_route_access_unauthenticated_user(): void
    {
        $this->assertUnauthorizedAccess(method: $this->method, uri: $this->uri, headers: $this->headers);
    }

    public function test_it_should_show_vouchers(): void
    {
        Voucher::factory(10)
            ->create(['user_id' => $this->user->id]);

        Sanctum::actingAs($this->user);

        $this->whenICallThisEndpoint(
            method: $this->method,
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_OK);
        $this->thenIExpectAResponseStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                    'code',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->thenIExpectInDatabase('vouchers', ['user_id' => $this->user->id]);
    }
}
