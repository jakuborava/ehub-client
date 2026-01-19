<?php

use JakubOrava\EhubClient\Requests\CreativesListRequest;

it('builds creative list request with all parameters', function () {
    $request = (new CreativesListRequest)
        ->page(1)
        ->perPage(50)
        ->id('creative-id')
        ->campaignId('campaign-id')
        ->type('link')
        ->name('Default Link')
        ->sort('-id');

    $params = $request->toArray();

    expect($params)->toBe([
        'page' => 1,
        'perPage' => 50,
        'sort' => '-id',
        'id' => 'creative-id',
        'campaignId' => 'campaign-id',
        'type' => 'link',
        'name' => 'Default Link',
    ]);
});

it('supports method chaining', function () {
    $request = (new CreativesListRequest)
        ->campaignId('campaign-id')
        ->type('banner');

    expect($request)->toBeInstanceOf(CreativesListRequest::class);
});
