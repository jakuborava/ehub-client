<?php

use JakubOrava\EhubClient\DTO\Publisher\VoucherDTO;

it('creates VoucherDTO from array', function () {
    $data = [
        'id' => 1,
        'campaignId' => '654ba4aa',
        'campaignName' => 'Test Campaign',
        'code' => 'ABC10',
        'type' => 'voucher',
        'rules' => '10% discount',
        'url' => 'https://example.com/voucher',
        'destinationUrl' => 'https://example.com',
        'validFrom' => '2024-01-01',
        'validTill' => '2024-12-31',
        'isValid' => true,
    ];

    $dto = VoucherDTO::fromArray($data);

    expect($dto->id)->toBe(1)
        ->and($dto->campaignId)->toBe('654ba4aa')
        ->and($dto->campaignName)->toBe('Test Campaign')
        ->and($dto->code)->toBe('ABC10')
        ->and($dto->type)->toBe('voucher')
        ->and($dto->rules)->toBe('10% discount')
        ->and($dto->url)->toBe('https://example.com/voucher')
        ->and($dto->destinationUrl)->toBe('https://example.com')
        ->and($dto->validFrom->toDateString())->toBe('2024-01-01')
        ->and($dto->validTill->toDateString())->toBe('2024-12-31')
        ->and($dto->isValid)->toBeTrue();
});

it('creates VoucherDTO with null isValid', function () {
    $data = [
        'id' => 1,
        'campaignId' => '654ba4aa',
        'campaignName' => 'Test Campaign',
        'code' => 'ABC10',
        'type' => 'voucher',
        'rules' => '10% discount',
        'url' => 'https://example.com/voucher',
        'destinationUrl' => 'https://example.com',
        'validFrom' => '2024-01-01',
        'validTill' => '2024-12-31',
    ];

    $dto = VoucherDTO::fromArray($data);

    expect($dto->isValid)->toBeNull();
});
