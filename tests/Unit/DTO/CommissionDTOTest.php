<?php

use JakubOrava\EhubClient\DTO\Publisher\CommissionDTO;

it('creates CommissionDTO from array with all fields', function () {
    $data = [
        'commissionType' => 'PPS',
        'name' => 'Default Commission',
        'valueType' => '%',
        'value' => 8.5,
    ];

    $dto = CommissionDTO::fromArray($data);

    expect($dto->commissionType)->toBe('PPS')
        ->and($dto->name)->toBe('Default Commission')
        ->and($dto->valueType)->toBe('%')
        ->and($dto->value)->toBe(8.5);
});

it('creates CommissionDTO with null name', function () {
    $data = [
        'commissionType' => 'PPS',
        'name' => null,
        'valueType' => '%',
        'value' => 10.0,
    ];

    $dto = CommissionDTO::fromArray($data);

    expect($dto->commissionType)->toBe('PPS')
        ->and($dto->name)->toBeNull()
        ->and($dto->valueType)->toBe('%')
        ->and($dto->value)->toBe(10.0);
});

it('creates CommissionDTO with PPC commission type', function () {
    $data = [
        'commissionType' => 'PPC',
        'name' => 'Click Commission',
        'valueType' => 'CZK',
        'value' => 5.0,
    ];

    $dto = CommissionDTO::fromArray($data);

    expect($dto->commissionType)->toBe('PPC')
        ->and($dto->name)->toBe('Click Commission')
        ->and($dto->valueType)->toBe('CZK')
        ->and($dto->value)->toBe(5.0);
});

it('creates CommissionDTO with PPL commission type', function () {
    $data = [
        'commissionType' => 'PPL',
        'name' => 'Lead Commission',
        'valueType' => '%',
        'value' => 15.5,
    ];

    $dto = CommissionDTO::fromArray($data);

    expect($dto->commissionType)->toBe('PPL')
        ->and($dto->value)->toBe(15.5);
});

it('converts int value to float', function () {
    $data = [
        'commissionType' => 'PPS',
        'name' => null,
        'valueType' => '%',
        'value' => 10,
    ];

    $dto = CommissionDTO::fromArray($data);

    expect($dto->value)->toBe(10.0)
        ->and($dto->value)->toBeFloat();
});

it('is readonly', function () {
    $data = [
        'commissionType' => 'PPS',
        'name' => null,
        'valueType' => '%',
        'value' => 8.5,
    ];

    $dto = CommissionDTO::fromArray($data);

    expect(function () use ($dto) {
        $dto->value = 100.0;
    })->toThrow(Error::class);
});
