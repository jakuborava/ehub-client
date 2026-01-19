<?php

use Illuminate\Support\Facades\Http;
use JakubOrava\EhubClient\EhubClient;
use JakubOrava\EhubClient\Requests\TransactionsListRequest;

beforeEach(function () {
    $this->client = new EhubClient();
    $this->publisherId = 'test-publisher-id';
});

it('fetches transactions list successfully', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/transactions*' => Http::response([
            'transactions' => [
                [
                    'id' => 'b49feb8a',
                    'uuid' => '36130a28-9ade-49c0-9560-6a36ea1a64cd',
                    'dateInserted' => '2024-01-24T14:52:07+0100',
                    'campaignId' => '654ba4aa',
                    'creativeId' => '4eb23ba6',
                    'type' => 'PPS',
                    'code' => 'special',
                    'commission' => 100.0,
                    'amount' => 1000.0,
                    'ip' => '1.2.3.4',
                    'clickCount' => 2,
                    'firstClickDateTime' => '2024-01-24T14:52:07+0100',
                    'firstClickIp' => '1.2.3.4',
                    'firstClickData1' => 'data1',
                    'firstClickData2' => '',
                    'lastClickDateTime' => '2024-01-24T15:54:01+0100',
                    'lastClickIp' => '1.2.4.5',
                    'lastClickData1' => 'data1',
                    'lastClickData2' => '',
                    'lastClickReferrerUrl' => 'https://publisher.cz/link',
                    'orderId' => 'ORD123',
                    'productId' => 'P456',
                    'newCustomer' => true,
                    'resolutionDateTime' => null,
                    'status' => 'pending',
                    'payoutStatus' => 'unpaid',
                ],
            ],
            'totalItems' => 1,
        ], 200),
    ]);

    $response = $this->client->publishers()
        ->transactions()
        ->list($this->publisherId);

    expect($response->totalItems)->toBe(1)
        ->and($response->items)->toHaveCount(1)
        ->and($response->items->first()->id)->toBe('b49feb8a')
        ->and($response->items->first()->uuid)->toBe('36130a28-9ade-49c0-9560-6a36ea1a64cd')
        ->and($response->items->first()->commission)->toBe(100.0)
        ->and($response->items->first()->amount)->toBe(1000.0)
        ->and($response->items->first()->status)->toBe('pending')
        ->and($response->items->first()->newCustomer)->toBeTrue();
});

it('fetches single transaction detail', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/transactions/*' => Http::response([
            'id' => 'b49feb8a',
            'uuid' => '36130a28-9ade-49c0-9560-6a36ea1a64cd',
            'dateInserted' => '2024-01-24T14:52:07+0100',
            'campaignId' => '654ba4aa',
            'creativeId' => '4eb23ba6',
            'type' => 'PPS',
            'code' => 'special',
            'commission' => 100.0,
            'amount' => 1000.0,
            'ip' => '1.2.3.4',
            'clickCount' => 2,
            'firstClickDateTime' => '2024-01-24T14:52:07+0100',
            'firstClickIp' => '1.2.3.4',
            'firstClickData1' => 'data1',
            'firstClickData2' => '',
            'lastClickDateTime' => '2024-01-24T15:54:01+0100',
            'lastClickIp' => '1.2.4.5',
            'lastClickData1' => 'data1',
            'lastClickData2' => '',
            'lastClickReferrerUrl' => 'https://publisher.cz/link',
            'orderId' => 'ORD123',
            'productId' => 'P456',
            'newCustomer' => true,
            'resolutionDateTime' => null,
            'status' => 'pending',
            'payoutStatus' => 'unpaid',
        ], 200),
    ]);

    $transaction = $this->client->publishers()
        ->transactions()
        ->get($this->publisherId, 'b49feb8a');

    expect($transaction->id)->toBe('b49feb8a')
        ->and($transaction->uuid)->toBe('36130a28-9ade-49c0-9560-6a36ea1a64cd')
        ->and($transaction->orderId)->toBe('ORD123')
        ->and($transaction->productId)->toBe('P456');
});

it('sends correct query parameters with date filters', function () {
    Http::fake([
        'api.ehub.cz/v3/publishers/*/transactions*' => Http::response([
            'transactions' => [],
            'totalItems' => 0,
        ], 200),
    ]);

    $request = (new TransactionsListRequest())
        ->dateInsertedFrom('2024-01-01T00:00:00')
        ->dateInsertedTo('2024-01-31T23:59:59')
        ->status('approved')
        ->payoutStatus('paid');

    $this->client->publishers()
        ->transactions()
        ->list($this->publisherId, $request);

    Http::assertSent(function ($request) {
        $url = $request->url();
        return str_contains($url, 'https://api.ehub.cz/v3/publishers/test-publisher-id/transactions') &&
            str_contains($url, 'dateInsertedFrom=2024-01-01T00%3A00%3A00') &&
            str_contains($url, 'dateInsertedTo=2024-01-31T23%3A59%3A59') &&
            str_contains($url, 'status=approved') &&
            str_contains($url, 'payoutStatus=paid');
    });
});
