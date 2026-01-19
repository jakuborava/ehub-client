<?php

use JakubOrava\EhubClient\Requests\TransactionsListRequest;

it('builds transaction list request with all parameters', function () {
    $request = (new TransactionsListRequest)
        ->page(2)
        ->perPage(50)
        ->dateInsertedFrom('2024-01-01T00:00:00')
        ->dateInsertedTo('2024-01-31T23:59:59')
        ->campaignId('campaign-id')
        ->creativeId('creative-id')
        ->type('PPS')
        ->code('special')
        ->orderId('ORD123')
        ->productId('P456')
        ->newCustomer(true)
        ->resolutionFrom('2024-01-01T00:00:00')
        ->resolutionTo('2024-01-31T23:59:59')
        ->status('approved')
        ->payoutStatus('paid')
        ->sort('-dateInserted');

    $params = $request->toArray();

    expect($params)->toBe([
        'page' => 2,
        'perPage' => 50,
        'sort' => '-dateInserted',
        'dateInsertedFrom' => '2024-01-01T00:00:00',
        'dateInsertedTo' => '2024-01-31T23:59:59',
        'campaignId' => 'campaign-id',
        'creativeId' => 'creative-id',
        'type' => 'PPS',
        'code' => 'special',
        'orderId' => 'ORD123',
        'productId' => 'P456',
        'newCustomer' => true,
        'resolutionFrom' => '2024-01-01T00:00:00',
        'resolutionTo' => '2024-01-31T23:59:59',
        'status' => 'approved',
        'payoutStatus' => 'paid',
    ]);
});

it('supports method chaining', function () {
    $request = (new TransactionsListRequest)
        ->status('approved')
        ->payoutStatus('paid');

    expect($request)->toBeInstanceOf(TransactionsListRequest::class);
});
