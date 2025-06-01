<?php

namespace Tests\Feature\Api\Vouchers;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\BaseTestCase;

class GenerateVoucherTest extends BaseTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()
            ->where('email', 'test@example.com')
            ->first();

        $this->givenIHaveThisRoute(route('api.vouchers.generate'));
    }

    public function test_it_should_not_generate_a_voucher(): void
    {
        Voucher::factory(10)
            ->create(
                attributes: ['user_id' => $this->user->id],
            );

        Sanctum::actingAs($this->user);

        $this->whenICallThisEndpoint(
            method: 'post',
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_BAD_REQUEST);
    }

    public function test_it_should_generate_a_voucher(): void
    {
        Sanctum::actingAs($this->user);

        $this->whenICallThisEndpoint(
            method: 'post',
            uri: $this->uri,
            headers: $this->headers
        );

        $this->thenIExpectAResponse(Response::HTTP_CREATED);
    }
}
