<?php

use Illuminate\Support\Facades\Http;
use JakubOrava\EhubClient\EhubClient;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Requests\VouchersListRequest;

beforeEach(function () {
    $this->client = new EhubClient();
    $this->publisherId = 'test-publisher-id';
});

it('fetches vouchers list successfully', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/vouchers*' => Http::response([
            'vouchers' => [
                [
                    'id' => 1,
                    'campaignId' => '654ba4aa',
                    'campaignName' => 'Test Campaign',
                    'code' => 'ABC10',
                    'type' => 'voucher',
                    'rules' => '10% discount',
                    'url' => 'https://example.com/voucher',
                    'destinationUrl' => 'https://example.com',
                    'validFrom' => '2024-01-01',
                    'validTill' => '2024-12-31',
                    'isValid' => true,
                ],
                [
                    'id' => 2,
                    'campaignId' => '654ba4aa',
                    'campaignName' => 'Test Campaign',
                    'code' => 'ABC20',
                    'type' => 'action',
                    'rules' => '20% discount',
                    'url' => 'https://example.com/action',
                    'destinationUrl' => 'https://example.com',
                    'validFrom' => '2024-01-01',
                    'validTill' => '2024-12-31',
                    'isValid' => false,
                ],
            ],
            'totalItems' => 2,
        ], 200),
    ]);

    $response = $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId);

    expect($response->totalItems)->toBe(2)
        ->and($response->items)->toHaveCount(2)
        ->and($response->items->first()->code)->toBe('ABC10')
        ->and($response->items->first()->campaignName)->toBe('Test Campaign')
        ->and($response->items->first()->type)->toBe('voucher')
        ->and($response->items->last()->code)->toBe('ABC20')
        ->and($response->items->last()->type)->toBe('action');
});

it('sends correct query parameters with request object', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/vouchers*' => Http::response([
            'vouchers' => [],
            'totalItems' => 0,
        ], 200),
    ]);

    $request = (new VouchersListRequest())
        ->page(2)
        ->perPage(100)
        ->type('voucher')
        ->isValid(true)
        ->sort('-validFrom');

    $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId, $request);

    Http::assertSent(function ($request) {
        $url = $request->url();
        return str_contains($url, 'https://api.ehub.cz/v3/publishers/test-publisher-id/vouchers') &&
            str_contains($url, 'apiKey=test-api-key') &&
            str_contains($url, 'page=2') &&
            str_contains($url, 'perPage=100') &&
            str_contains($url, 'type=voucher') &&
            str_contains($url, 'isValid=1') &&
            str_contains($url, 'sort=-validFrom');
    });
});

it('throws AuthenticationException on 401 response', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/vouchers*' => Http::response([], 401),
    ]);

    $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId);
})->throws(AuthenticationException::class);

it('handles pagination metadata correctly', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/vouchers*' => Http::response([
            'vouchers' => [],
            'totalItems' => 150,
        ], 200),
    ]);

    $request = (new VouchersListRequest())
        ->page(2)
        ->perPage(50);

    $response = $this->client->publishers()
        ->vouchers()
        ->list($this->publisherId, $request);

    expect($response->totalItems)->toBe(150)
        ->and($response->currentPage)->toBe(2)
        ->and($response->perPage)->toBe(50)
        ->and($response->getTotalPages())->toBe(3)
        ->and($response->hasMorePages())->toBeTrue();
});
