<?php

use JakubOrava\EhubClient\Requests\CampaignsListRequest;

it('builds campaign list request with all parameters', function () {
    $request = (new CampaignsListRequest())
        ->page(1)
        ->perPage(50)
        ->name('Test Campaign')
        ->categories([4, 6, 8])
        ->status('approved')
        ->sort('name');

    $params = $request->toArray();

    expect($params)->toBe([
        'page' => 1,
        'perPage' => 50,
        'sort' => 'name',
        'name' => 'Test Campaign',
        'categories' => '4,6,8',
        'status' => 'approved',
    ]);
});

it('converts categories array to comma-separated string', function () {
    $request = (new CampaignsListRequest())
        ->categories([3, 4, 5]);

    $params = $request->toArray();

    expect($params['categories'])->toBe('3,4,5');
});
