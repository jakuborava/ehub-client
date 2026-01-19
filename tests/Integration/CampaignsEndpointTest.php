<?php

use Illuminate\Support\Facades\Http;
use JakubOrava\EhubClient\EhubClient;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Requests\CampaignsListRequest;

beforeEach(function () {
    $this->client = new EhubClient();
    $this->publisherId = 'test-publisher-id';
});

it('fetches campaigns list successfully', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/campaigns*' => Http::response([
            'campaigns' => [
                [
                    'id' => '12acea8d',
                    'name' => 'Test Campaign',
                    'logoUrl' => 'https://example.com/logo.png',
                    'web' => 'https://example.com',
                    'country' => 'CZ',
                    'categories' => [
                        ['code' => 4, 'name' => 'Finance'],
                    ],
                    'description' => 'Test description',
                    'commissionDescription' => 'Without VAT',
                    'commissionGroups' => [
                        [
                            'name' => 'Default',
                            'status' => 'approved',
                            'commissions' => [
                                [
                                    'commissionType' => 'PPS',
                                    'name' => null,
                                    'valueType' => '%',
                                    'value' => 8.0,
                                ],
                            ],
                        ],
                    ],
                    'restrictions' => [],
                    'averageAmount' => 1000.0,
                    'cookieLifetime' => 30,
                    'maxApprovalInterval' => 120,
                    'tracking' => 'advanced',
                    'domainTracking' => true,
                    'hasFeed' => false,
                    'defaultLink' => null,
                ],
            ],
            'totalItems' => 1,
        ], 200),
    ]);

    $response = $this->client->publishers()
        ->campaigns()
        ->list($this->publisherId);

    expect($response->totalItems)->toBe(1)
        ->and($response->items)->toHaveCount(1)
        ->and($response->items->first()->id)->toBe('12acea8d')
        ->and($response->items->first()->name)->toBe('Test Campaign')
        ->and($response->items->first()->categories)->toHaveCount(1)
        ->and($response->items->first()->categories->first()->code)->toBe(4)
        ->and($response->items->first()->commissionGroups)->toHaveCount(1)
        ->and($response->items->first()->commissionGroups->first()->commissions)->toHaveCount(1)
        ->and($response->items->first()->domainTracking)->toBeTrue()
        ->and($response->items->first()->hasFeed)->toBeFalse();
});

it('sends correct query parameters with filters', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/campaigns*' => Http::response([
            'campaigns' => [],
            'totalItems' => 0,
        ], 200),
    ]);

    $request = (new CampaignsListRequest())
        ->name('Test')
        ->categories([4, 6])
        ->status('approved');

    $this->client->publishers()
        ->campaigns()
        ->list($this->publisherId, $request);

    Http::assertSent(function ($request) {
        $url = $request->url();
        return str_contains($url, 'https://api.ehub.cz/v3/publishers/test-publisher-id/campaigns') &&
            str_contains($url, 'name=Test') &&
            str_contains($url, 'categories=4%2C6') &&
            str_contains($url, 'status=approved');
    });
});

it('throws ApiErrorException on 404 response', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/campaigns*' => Http::response([], 404),
    ]);

    $this->client->publishers()
        ->campaigns()
        ->list($this->publisherId);
})->throws(ApiErrorException::class);
