<?php

use JakubOrava\EhubClient\DTO\Publisher\CampaignDTO;

it('creates CampaignDTO from array with nested collections', function () {
    $data = [
        'id' => '12acea8d',
        'name' => 'Test Campaign',
        'logoUrl' => 'https://example.com/logo.png',
        'web' => 'https://example.com',
        'country' => 'CZ',
        'categories' => [
            ['code' => 4, 'name' => 'Finance'],
            ['code' => 6, 'name' => 'E-shops'],
        ],
        'description' => 'Test description',
        'commissionDescription' => 'Without VAT',
        'commissionGroups' => [
            [
                'name' => 'Default',
                'status' => 'approved',
                'commissions' => [
                    [
                        'commissionType' => 'PPS',
                        'name' => null,
                        'valueType' => '%',
                        'value' => 8.5,
                    ],
                ],
            ],
        ],
        'restrictions' => [
            [
                'name' => 'No PPC',
                'description' => 'PPC forbidden',
                'note' => 'Some note',
            ],
        ],
        'averageAmount' => 1000.50,
        'cookieLifetime' => 30,
        'maxApprovalInterval' => 120,
        'tracking' => 'advanced',
        'domainTracking' => true,
        'hasFeed' => false,
        'defaultLink' => 'https://example.com/default',
    ];

    $dto = CampaignDTO::fromArray($data);

    expect($dto->id)->toBe('12acea8d')
        ->and($dto->name)->toBe('Test Campaign')
        ->and($dto->logoUrl)->toBe('https://example.com/logo.png')
        ->and($dto->web)->toBe('https://example.com')
        ->and($dto->country)->toBe('CZ')
        ->and($dto->categories)->toHaveCount(2)
        ->and($dto->categories->first()->code)->toBe(4)
        ->and($dto->categories->first()->name)->toBe('Finance')
        ->and($dto->commissionGroups)->toHaveCount(1)
        ->and($dto->commissionGroups->first()->name)->toBe('Default')
        ->and($dto->commissionGroups->first()->status)->toBe('approved')
        ->and($dto->commissionGroups->first()->commissions)->toHaveCount(1)
        ->and($dto->commissionGroups->first()->commissions->first()->commissionType)->toBe('PPS')
        ->and($dto->commissionGroups->first()->commissions->first()->value)->toBe(8.5)
        ->and($dto->restrictions)->toHaveCount(1)
        ->and($dto->restrictions->first()->name)->toBe('No PPC')
        ->and($dto->averageAmount)->toBe(1000.50)
        ->and($dto->cookieLifetime)->toBe(30)
        ->and($dto->domainTracking)->toBeTrue()
        ->and($dto->hasFeed)->toBeFalse();
});

it('creates CampaignDTO with minimal data', function () {
    $data = [
        'id' => '12acea8d',
        'name' => 'Test Campaign',
        'logoUrl' => null,
        'web' => null,
        'country' => null,
        'categories' => [],
        'description' => null,
        'commissionDescription' => null,
        'commissionGroups' => [],
        'restrictions' => [],
        'averageAmount' => null,
        'cookieLifetime' => null,
        'maxApprovalInterval' => null,
        'tracking' => null,
        'domainTracking' => null,
        'hasFeed' => null,
        'defaultLink' => null,
    ];

    $dto = CampaignDTO::fromArray($data);

    expect($dto->id)->toBe('12acea8d')
        ->and($dto->name)->toBe('Test Campaign')
        ->and($dto->logoUrl)->toBeNull()
        ->and($dto->categories)->toHaveCount(0)
        ->and($dto->commissionGroups)->toHaveCount(0)
        ->and($dto->restrictions)->toHaveCount(0);
});
