<?php

use JakubOrava\EhubClient\Requests\OutboundClicksListRequest;

it('builds outbound clicks list request with all parameters', function () {
    $request = (new OutboundClicksListRequest())
        ->page(1)
        ->perPage(100)
        ->from('2024-01-01T00:00:00')
        ->to('2024-01-31T23:59:59')
        ->campaignId('campaign-id')
        ->clickData1('data1')
        ->clickData2('data2')
        ->linkId('link-id')
        ->processed(true)
        ->sort('-dateTime');

    $params = $request->toArray();

    expect($params)->toBe([
        'page' => 1,
        'perPage' => 100,
        'sort' => '-dateTime',
        'from' => '2024-01-01T00:00:00',
        'to' => '2024-01-31T23:59:59',
        'campaignId' => 'campaign-id',
        'clickData1' => 'data1',
        'clickData2' => 'data2',
        'linkId' => 'link-id',
        'processed' => true,
    ]);
});

it('supports method chaining', function () {
    $request = (new OutboundClicksListRequest())
        ->from('2024-01-01T00:00:00')
        ->to('2024-01-31T23:59:59');

    expect($request)->toBeInstanceOf(OutboundClicksListRequest::class);
});
