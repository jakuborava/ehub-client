<?php

use JakubOrava\EhubClient\DTO\Publisher\CommissionGroupDTO;

it('creates CommissionGroupDTO from array with commissions', function () {
    $data = [
        'name' => 'Default',
        'status' => 'approved',
        'commissions' => [
            [
                'commissionType' => 'PPS',
                'name' => null,
                'valueType' => '%',
                'value' => 8.5,
            ],
            [
                'commissionType' => 'PPC',
                'name' => 'Click bonus',
                'valueType' => 'CZK',
                'value' => 2.0,
            ],
        ],
    ];

    $dto = CommissionGroupDTO::fromArray($data);

    expect($dto->name)->toBe('Default')
        ->and($dto->status)->toBe('approved')
        ->and($dto->commissions)->toHaveCount(2)
        ->and($dto->commissions->first()->commissionType)->toBe('PPS')
        ->and($dto->commissions->first()->value)->toBe(8.5)
        ->and($dto->commissions->last()->commissionType)->toBe('PPC')
        ->and($dto->commissions->last()->name)->toBe('Click bonus');
});

it('creates CommissionGroupDTO with null status', function () {
    $data = [
        'name' => 'Premium',
        'status' => null,
        'commissions' => [],
    ];

    $dto = CommissionGroupDTO::fromArray($data);

    expect($dto->name)->toBe('Premium')
        ->and($dto->status)->toBeNull()
        ->and($dto->commissions)->toHaveCount(0);
});

it('creates CommissionGroupDTO with empty commissions', function () {
    $data = [
        'name' => 'Standard',
        'status' => 'pending',
        'commissions' => [],
    ];

    $dto = CommissionGroupDTO::fromArray($data);

    expect($dto->name)->toBe('Standard')
        ->and($dto->status)->toBe('pending')
        ->and($dto->commissions)->toHaveCount(0);
});

it('creates CommissionGroupDTO with single commission', function () {
    $data = [
        'name' => 'Basic',
        'status' => 'approved',
        'commissions' => [
            [
                'commissionType' => 'PPS',
                'name' => null,
                'valueType' => '%',
                'value' => 5.0,
            ],
        ],
    ];

    $dto = CommissionGroupDTO::fromArray($data);

    expect($dto->commissions)->toHaveCount(1)
        ->and($dto->commissions->first()->value)->toBe(5.0);
});

it('creates CommissionGroupDTO with multiple commission types', function () {
    $data = [
        'name' => 'Mixed',
        'status' => 'approved',
        'commissions' => [
            [
                'commissionType' => 'PPS',
                'name' => 'Sale commission',
                'valueType' => '%',
                'value' => 10.0,
            ],
            [
                'commissionType' => 'PPC',
                'name' => 'Click commission',
                'valueType' => 'CZK',
                'value' => 1.5,
            ],
            [
                'commissionType' => 'PPL',
                'name' => 'Lead commission',
                'valueType' => 'CZK',
                'value' => 50.0,
            ],
        ],
    ];

    $dto = CommissionGroupDTO::fromArray($data);

    expect($dto->commissions)->toHaveCount(3)
        ->and($dto->commissions->pluck('commissionType')->toArray())
        ->toBe(['PPS', 'PPC', 'PPL']);
});

it('is readonly', function () {
    $data = [
        'name' => 'Default',
        'status' => 'approved',
        'commissions' => [],
    ];

    $dto = CommissionGroupDTO::fromArray($data);

    expect(function () use ($dto) {
        $dto->name = 'Changed';
    })->toThrow(Error::class);
});
