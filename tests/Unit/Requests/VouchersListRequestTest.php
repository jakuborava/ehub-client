<?php

use JakubOrava\EhubClient\Requests\VouchersListRequest;

it('builds voucher list request with all parameters', function () {
    $request = (new VouchersListRequest)
        ->page(2)
        ->perPage(100)
        ->type('voucher')
        ->isValid(true)
        ->sort('-validFrom')
        ->fields(['id', 'code', 'campaignName']);

    $params = $request->toArray();

    expect($params)->toBe([
        'page' => 2,
        'perPage' => 100,
        'sort' => '-validFrom',
        'fields' => 'id,code,campaignName',
        'type' => 'voucher',
        'isValid' => true,
    ]);
});

it('supports method chaining', function () {
    $request = (new VouchersListRequest)
        ->type('action')
        ->isValid(false);

    expect($request)->toBeInstanceOf(VouchersListRequest::class);
});
