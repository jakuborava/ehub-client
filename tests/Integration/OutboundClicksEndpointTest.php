<?php

use Illuminate\Support\Facades\Http;
use JakubOrava\EhubClient\EhubClient;
use JakubOrava\EhubClient\Requests\OutboundClicksListRequest;

beforeEach(function () {
    $this->client = new EhubClient();
    $this->publisherId = 'test-publisher-id';
});

it('fetches outbound clicks list successfully', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/outboundClicks*' => Http::response([
            'outboundClicks' => [
                [
                    'id' => 'd0c75e9d-300b-4cf7-849b-c56db45d76b7',
                    'dateTime' => '2023-06-10T17:11:33+0100',
                    'campaignId' => '654ba4aa',
                    'clickDateTime' => '2023-06-10T17:05:45+0100',
                    'clickReferrerUrl' => 'https://publisher.com',
                    'clickData1' => 'some_data',
                    'clickData2' => 'other_data',
                    'linkId' => 'outbound_click_01',
                    'commission' => 1.5,
                    'processed' => true,
                ],
                [
                    'id' => 'e1d86fae-411c-5dg8-a05a-d47eb56e87c8',
                    'dateTime' => '2023-06-11T10:20:15+0100',
                    'campaignId' => '654ba4aa',
                    'clickDateTime' => '2023-06-11T10:15:30+0100',
                    'clickReferrerUrl' => 'https://publisher.com/page',
                    'clickData1' => 'data_1',
                    'clickData2' => null,
                    'linkId' => 'outbound_click_02',
                    'commission' => 2.0,
                    'processed' => false,
                ],
            ],
            'totalItems' => 2,
        ], 200),
    ]);

    $response = $this->client->publishers()
        ->outboundClicks()
        ->list($this->publisherId);

    expect($response->totalItems)->toBe(2)
        ->and($response->items)->toHaveCount(2)
        ->and($response->items->first()->id)->toBe('d0c75e9d-300b-4cf7-849b-c56db45d76b7')
        ->and($response->items->first()->campaignId)->toBe('654ba4aa')
        ->and($response->items->first()->commission)->toBe(1.5)
        ->and($response->items->first()->processed)->toBeTrue()
        ->and($response->items->last()->id)->toBe('e1d86fae-411c-5dg8-a05a-d47eb56e87c8')
        ->and($response->items->last()->commission)->toBe(2.0)
        ->and($response->items->last()->processed)->toBeFalse();
});

it('sends correct query parameters with date filters', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/outboundClicks*' => Http::response([
            'outboundClicks' => [],
            'totalItems' => 0,
        ], 200),
    ]);

    $request = (new OutboundClicksListRequest())
        ->from('2024-01-01T00:00:00')
        ->to('2024-01-31T23:59:59')
        ->campaignId('campaign-id')
        ->processed(true)
        ->sort('-dateTime');

    $this->client->publishers()
        ->outboundClicks()
        ->list($this->publisherId, $request);

    Http::assertSent(function ($request) {
        $url = $request->url();
        return str_contains($url, 'https://api.ehub.cz/v3/publishers/test-publisher-id/outboundClicks') &&
            str_contains($url, 'from=2024-01-01T00%3A00%3A00') &&
            str_contains($url, 'to=2024-01-31T23%3A59%3A59') &&
            str_contains($url, 'campaignId=campaign-id') &&
            str_contains($url, 'processed=1') &&
            str_contains($url, 'sort=-dateTime');
    });
});

it('handles empty outbound clicks list', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/outboundClicks*' => Http::response([
            'outboundClicks' => [],
            'totalItems' => 0,
        ], 200),
    ]);

    $response = $this->client->publishers()
        ->outboundClicks()
        ->list($this->publisherId);

    expect($response->totalItems)->toBe(0)
        ->and($response->items)->toHaveCount(0);
});

it('handles clicks with null optional fields', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/outboundClicks*' => Http::response([
            'outboundClicks' => [
                [
                    'id' => 'd0c75e9d-300b-4cf7-849b-c56db45d76b7',
                    'dateTime' => '2023-06-10T17:11:33+0100',
                    'campaignId' => '654ba4aa',
                    'clickDateTime' => '2023-06-10T17:05:45+0100',
                    'clickReferrerUrl' => null,
                    'clickData1' => null,
                    'clickData2' => null,
                    'linkId' => null,
                    'commission' => null,
                    'processed' => null,
                ],
            ],
            'totalItems' => 1,
        ], 200),
    ]);

    $response = $this->client->publishers()
        ->outboundClicks()
        ->list($this->publisherId);

    expect($response->items->first()->clickReferrerUrl)->toBeNull()
        ->and($response->items->first()->clickData1)->toBeNull()
        ->and($response->items->first()->commission)->toBeNull()
        ->and($response->items->first()->processed)->toBeNull();
});
