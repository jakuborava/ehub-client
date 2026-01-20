<?php

use Illuminate\Support\Facades\Http;
use JakubOrava\EhubClient\EhubClient;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;
use JakubOrava\EhubClient\Exceptions\ValidationException;

beforeEach(function () {
    config(['ehub-client.api_key' => 'test-api-key']);
    $this->client = new EhubClient;
    $this->publisherId = 'test-publisher-id';
});

it('throws AuthenticationException on 401 response', function () {
    Http::fake([
        '*' => Http::response([], 401),
    ]);

    $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId);
})->throws(AuthenticationException::class, 'Authentication failed');

it('throws ValidationException on 422 response', function () {
    Http::fake([
        '*' => Http::response([
            'message' => 'Invalid parameters',
        ], 422),
    ]);

    $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId);
})->throws(ValidationException::class);

it('throws ApiErrorException on 403 response', function () {
    Http::fake([
        '*' => Http::response([
            'message' => 'Access forbidden',
        ], 403),
    ]);

    $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId);
})->throws(ApiErrorException::class, 'Access forbidden');

it('throws ApiErrorException on 404 response', function () {
    Http::fake([
        '*' => Http::response([], 404),
    ]);

    $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId);
})->throws(ApiErrorException::class, 'Resource not found');

it('throws ApiErrorException on 500 response', function () {
    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId);
})->throws(ApiErrorException::class, 'Server error occurred');

it('throws UnexpectedResponseException when response is not an array', function () {
    Http::fake([
        '*' => Http::response('not an array', 200),
    ]);

    $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId);
})->throws(UnexpectedResponseException::class, 'Response is not an array');

it('extracts error message from response', function () {
    Http::fake([
        '*' => Http::response([
            'message' => 'Custom error message',
        ], 403),
    ]);

    try {
        $this->client->publishers()
            ->vouchers()
            ->list($this->publisherId);
    } catch (ApiErrorException $e) {
        expect($e->getMessage())->toContain('Custom error message')
            ->and($e->getCode())->toBe(403);
    }
});
