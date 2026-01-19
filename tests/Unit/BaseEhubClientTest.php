<?php

use Illuminate\Support\Facades\Http;
use JakubOrava\EhubClient\BaseEhubClient;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;
use JakubOrava\EhubClient\Exceptions\ValidationException;

beforeEach(function () {
    config(['ehub-client.api_key' => 'test-api-key']);
    $this->client = new BaseEhubClient;
    $this->userId = 'test-user-id';
});

it('builds correct URL with userId and path', function () {
    Http::fake([
        'https://api.ehub.cz/v3/publishers/test-user-id/vouchers*' => Http::response(['data' => 'test']),
    ]);

    $this->client->get($this->userId, 'vouchers');

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'https://api.ehub.cz/v3/publishers/test-user-id/vouchers');
    });
});

it('strips leading slash from path', function () {
    Http::fake([
        'https://api.ehub.cz/v3/publishers/test-user-id/vouchers*' => Http::response(['data' => 'test']),
    ]);

    $this->client->get($this->userId, '/vouchers');

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'https://api.ehub.cz/v3/publishers/test-user-id/vouchers')
            && ! str_contains($request->url(), '//vouchers');
    });
});

it('adds API key to query parameters for GET request', function () {
    Http::fake([
        '*' => Http::response(['data' => 'test']),
    ]);

    $this->client->get($this->userId, 'vouchers', ['perPage' => 50]);

    Http::assertSent(function ($request) {
        $query = $request->data();

        return isset($query['apiKey'])
            && $query['apiKey'] === 'test-api-key'
            && $query['perPage'] === 50;
    });
});

it('adds API key to request body for POST request', function () {
    Http::fake([
        '*' => Http::response(['data' => 'test']),
    ]);

    $this->client->post($this->userId, 'vouchers', ['name' => 'Test'], ['perPage' => 50]);

    Http::assertSent(function ($request) {
        $data = $request->data();

        return isset($data['apiKey'])
            && $data['apiKey'] === 'test-api-key'
            && $data['name'] === 'Test'
            && $data['perPage'] === 50;
    });
});

it('handles PATCH requests correctly', function () {
    Http::fake([
        '*' => Http::response(['data' => 'test']),
    ]);

    $this->client->patch($this->userId, 'vouchers/123', ['status' => 'active']);

    Http::assertSent(function ($request) {
        return $request->method() === 'PATCH'
            && str_contains($request->url(), 'vouchers/123');
    });
});

it('handles PUT requests correctly', function () {
    Http::fake([
        '*' => Http::response(['data' => 'test']),
    ]);

    $this->client->put($this->userId, 'vouchers/123', ['status' => 'active']);

    Http::assertSent(function ($request) {
        return $request->method() === 'PUT'
            && str_contains($request->url(), 'vouchers/123');
    });
});

it('handles DELETE requests correctly', function () {
    Http::fake([
        '*' => Http::response(['data' => 'test']),
    ]);

    $this->client->delete($this->userId, 'vouchers/123');

    Http::assertSent(function ($request) {
        return $request->method() === 'DELETE'
            && str_contains($request->url(), 'vouchers/123');
    });
});

it('throws AuthenticationException on 401 response', function () {
    Http::fake([
        '*' => Http::response([], 401),
    ]);

    $this->client->get($this->userId, 'vouchers');
})->throws(AuthenticationException::class, 'Authentication failed');

it('throws ValidationException on 422 response', function () {
    Http::fake([
        '*' => Http::response(['message' => 'Invalid parameters'], 422),
    ]);

    $this->client->get($this->userId, 'vouchers');
})->throws(ValidationException::class);

it('throws ApiErrorException with custom message on 403 response', function () {
    Http::fake([
        '*' => Http::response(['message' => 'Access denied to this resource'], 403),
    ]);

    try {
        $this->client->get($this->userId, 'vouchers');
    } catch (ApiErrorException $e) {
        expect($e->getMessage())->toContain('Access denied to this resource')
            ->and($e->getCode())->toBe(403);
    }
});

it('throws ApiErrorException on 404 response', function () {
    Http::fake([
        '*' => Http::response([], 404),
    ]);

    $this->client->get($this->userId, 'vouchers');
})->throws(ApiErrorException::class, 'Resource not found');

it('throws ApiErrorException on 500 response', function () {
    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $this->client->get($this->userId, 'vouchers');
})->throws(ApiErrorException::class, 'Server error occurred');

it('throws UnexpectedResponseException when response is not an array', function () {
    Http::fake([
        '*' => Http::response('not an array', 200),
    ]);

    $this->client->get($this->userId, 'vouchers');
})->throws(UnexpectedResponseException::class, 'Response is not an array');

it('extracts error message from message field', function () {
    Http::fake([
        '*' => Http::response(['message' => 'Custom error message'], 403),
    ]);

    try {
        $this->client->get($this->userId, 'vouchers');
    } catch (ApiErrorException $e) {
        expect($e->getMessage())->toContain('Custom error message');
    }
});

it('extracts error message from error field', function () {
    Http::fake([
        '*' => Http::response(['error' => 'Error from error field'], 403),
    ]);

    try {
        $this->client->get($this->userId, 'vouchers');
    } catch (ApiErrorException $e) {
        expect($e->getMessage())->toContain('Error from error field');
    }
});

it('falls back to response body when no message or error field', function () {
    Http::fake([
        '*' => Http::response(['some' => 'data'], 403),
    ]);

    try {
        $this->client->get($this->userId, 'vouchers');
    } catch (ApiErrorException $e) {
        expect($e->getMessage())->not->toBeEmpty();
    }
});

it('returns array response on successful request', function () {
    Http::fake([
        '*' => Http::response(['vouchers' => ['voucher1', 'voucher2'], 'totalItems' => 2]),
    ]);

    $result = $this->client->get($this->userId, 'vouchers');

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('vouchers')
        ->and($result)->toHaveKey('totalItems')
        ->and($result['totalItems'])->toBe(2);
});

it('sets correct HTTP headers', function () {
    Http::fake([
        '*' => Http::response(['data' => 'test']),
    ]);

    $this->client->get($this->userId, 'vouchers');

    Http::assertSent(function ($request) {
        return $request->hasHeader('Accept', 'application/json')
            && $request->hasHeader('Content-Type', 'application/json');
    });
});

it('handles query parameters with special characters', function () {
    Http::fake([
        '*' => Http::response(['data' => 'test']),
    ]);

    $this->client->get($this->userId, 'vouchers', [
        'name' => 'Test & Campaign',
        'sort' => '-validFrom',
    ]);

    Http::assertSent(function ($request) {
        $query = $request->data();

        return isset($query['name'])
            && $query['name'] === 'Test & Campaign'
            && $query['sort'] === '-validFrom';
    });
});

it('merges data and query params for POST request', function () {
    Http::fake([
        '*' => Http::response(['data' => 'test']),
    ]);

    $this->client->post(
        $this->userId,
        'vouchers',
        ['name' => 'Test Voucher', 'value' => 100],
        ['page' => 1, 'perPage' => 50]
    );

    Http::assertSent(function ($request) {
        $data = $request->data();

        return $data['name'] === 'Test Voucher'
            && $data['value'] === 100
            && $data['page'] === 1
            && $data['perPage'] === 50
            && isset($data['apiKey']);
    });
});
