<?php

use JakubOrava\EhubClient\DTO\Publisher\CampaignCategoryDTO;

it('creates CampaignCategoryDTO from array', function () {
    $data = [
        'code' => 4,
        'name' => 'Finance',
    ];

    $dto = CampaignCategoryDTO::fromArray($data);

    expect($dto->code)->toBe(4)
        ->and($dto->name)->toBe('Finance');
});

it('creates CampaignCategoryDTO with different category', function () {
    $data = [
        'code' => 6,
        'name' => 'E-shops',
    ];

    $dto = CampaignCategoryDTO::fromArray($data);

    expect($dto->code)->toBe(6)
        ->and($dto->name)->toBe('E-shops');
});

it('creates CampaignCategoryDTO with string code converted to int', function () {
    $data = [
        'code' => '12',
        'name' => 'Travel',
    ];

    $dto = CampaignCategoryDTO::fromArray($data);

    expect($dto->code)->toBe(12)
        ->and($dto->code)->toBeInt();
});

it('is readonly', function () {
    $data = [
        'code' => 4,
        'name' => 'Finance',
    ];

    $dto = CampaignCategoryDTO::fromArray($data);

    expect(function () use ($dto) {
        $dto->code = 999;
    })->toThrow(Error::class);
});
