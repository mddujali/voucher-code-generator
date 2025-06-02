<?php

namespace Tests\Traits\Feature;

use Illuminate\Http\Response;

trait AuthTest
{
    public function assertUnauthorizedAccess(string $method, string $uri, array $headers): void
    {
        $this->whenICallThisEndpoint(
            method: $method,
            uri: $uri,
            headers: $headers
        );

        $this->thenIExpectAResponse(Response::HTTP_UNAUTHORIZED);
        $this->thenIExpectAResponseStructure([
            'error_code',
            'message',
            'errors',
        ]);
    }
}
