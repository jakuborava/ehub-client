<?php

use Illuminate\Support\Facades\Http;
use JakubOrava\EhubClient\EhubClient;
use JakubOrava\EhubClient\Requests\CreativesListRequest;

beforeEach(function () {
    config(['ehub-client.api_key' => 'test-api-key']);
    $this->client = new EhubClient;
    $this->publisherId = 'test-publisher-id';
});

it('fetches creatives list successfully', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/creatives*' => Http::response([
            'creatives' => [
                [
                    'id' => '0002781B',
                    'campaignId' => '0000265X',
                    'type' => 'link',
                    'name' => 'Default Link',
                    'destinationUrl' => 'https://www.example.com',
                    'imageUrl' => null,
                ],
                [
                    'id' => '0002782B',
                    'campaignId' => '0000265X',
                    'type' => 'banner',
                    'name' => 'Banner 468x60',
                    'destinationUrl' => 'https://www.example.com',
                    'imageUrl' => 'https://example.com/banner.png',
                ],
            ],
            'totalItems' => 2,
        ], 200),
    ]);

    $response = $this->client->publishers()
        ->creatives()
        ->list($this->publisherId);

    expect($response->totalItems)->toBe(2)
        ->and($response->items)->toHaveCount(2)
        ->and($response->items->first()->id)->toBe('0002781B')
        ->and($response->items->first()->campaignId)->toBe('0000265X')
        ->and($response->items->first()->type)->toBe('link')
        ->and($response->items->first()->name)->toBe('Default Link')
        ->and($response->items->last()->id)->toBe('0002782B')
        ->and($response->items->last()->type)->toBe('banner')
        ->and($response->items->last()->imageUrl)->toBe('https://example.com/banner.png');
});

it('sends correct query parameters with filters', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/creatives*' => Http::response([
            'creatives' => [],
            'totalItems' => 0,
        ], 200),
    ]);

    $request = (new CreativesListRequest)
        ->campaignId('campaign-id')
        ->type('link')
        ->name('DefaultLink')
        ->sort('-id');

    $this->client->publishers()
        ->creatives()
        ->list($this->publisherId, $request);

    Http::assertSent(function ($request) {
        $url = $request->url();

        return str_contains($url, 'https://api.ehub.cz/v3/publishers/test-publisher-id/creatives') &&
            str_contains($url, 'campaignId=campaign-id') &&
            str_contains($url, 'type=link') &&
            str_contains($url, 'name=DefaultLink') &&
            str_contains($url, 'sort=-id');
    });
});

it('handles empty creatives list', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/creatives*' => Http::response([
            'creatives' => [],
            'totalItems' => 0,
        ], 200),
    ]);

    $response = $this->client->publishers()
        ->creatives()
        ->list($this->publisherId);

    expect($response->totalItems)->toBe(0)
        ->and($response->items)->toHaveCount(0);
});
