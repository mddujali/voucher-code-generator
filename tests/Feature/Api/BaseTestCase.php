<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    use RefreshDatabase;

    protected string $method;

    protected string $uri;

    protected array $headers = [
        'Accept' => 'application/json',
    ];

    protected TestResponse $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    protected function givenIHaveThisMethod(string $method): void
    {
        $this->method = $method;
    }

    protected function givenIHaveThisRoute(string $route): void
    {
        $this->uri = $route;
    }

    protected function givenIHaveTheseHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    protected function whenICallThisEndpoint(string $method, string $uri, array $data = [], array $headers = []): void
    {
        $this->response = $this->json(
            method: $method,
            uri: $uri,
            data: $data,
            headers: $headers,
        );
    }

    protected function thenIExpectAResponse(int $code): void
    {
        $this->response->assertStatus($code);
    }

    protected function thenIExpectAResponseStructure(array $structure): void
    {
        $this->response->assertJsonStructure($structure);
    }

    protected function thenIExpectInDatabase(string $table, array $data): void
    {
        $this->assertDatabaseHas($table, $data);
    }

    protected function thenIExpectDatabaseMissing(string $table, array $data): void
    {
        $this->assertDatabaseMissing($table, $data);
    }
}
