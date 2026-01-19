<?php

use JakubOrava\EhubClient\Requests\BaseRequest;

it('builds query parameters correctly', function () {
    $request = new class extends BaseRequest {};

    $params = $request
        ->page(2)
        ->perPage(50)
        ->sort('-name')
        ->fields(['id', 'name', 'code'])
        ->toArray();

    expect($params)->toBe([
        'page' => 2,
        'perPage' => 50,
        'sort' => '-name',
        'fields' => 'id,name,code',
    ]);
});

it('returns empty array when no parameters are set', function () {
    $request = new class extends BaseRequest {};

    expect($request->toArray())->toBe([]);
});

it('only includes non-null parameters', function () {
    $request = new class extends BaseRequest {};

    $params = $request
        ->page(1)
        ->toArray();

    expect($params)->toBe(['page' => 1])
        ->and($params)->not->toHaveKey('perPage')
        ->and($params)->not->toHaveKey('sort');
});

it('supports custom parameters via addParam', function () {
    $request = new class extends BaseRequest {};

    $params = $request
        ->addParam('customField', 'customValue')
        ->addParam('anotherField', 123)
        ->toArray();

    expect($params)->toBe([
        'customField' => 'customValue',
        'anotherField' => 123,
    ]);
});

it('filters out null custom parameters', function () {
    $request = new class extends BaseRequest {};

    $params = $request
        ->addParam('field1', 'value1')
        ->addParam('field2', null)
        ->toArray();

    expect($params)->toBe(['field1' => 'value1'])
        ->and($params)->not->toHaveKey('field2');
});
