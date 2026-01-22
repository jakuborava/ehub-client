<?php

use JakubOrava\EhubClient\DTO\Publisher\CampaignCategoryDTO;

it('creates CampaignCategoryDTO from array', function () {
    $data = [
        'id' => 4,
        'name' => 'Finance',
    ];

    $dto = CampaignCategoryDTO::fromArray($data);

    expect($dto->id)->toBe(4)
        ->and($dto->name)->toBe('Finance');
});

it('creates CampaignCategoryDTO with different category', function () {
    $data = [
        'id' => 6,
        'name' => 'E-shops',
    ];

    $dto = CampaignCategoryDTO::fromArray($data);

    expect($dto->id)->toBe(6)
        ->and($dto->name)->toBe('E-shops');
});

it('creates CampaignCategoryDTO with string id converted to int', function () {
    $data = [
        'id' => '12',
        'name' => 'Travel',
    ];

    $dto = CampaignCategoryDTO::fromArray($data);

    expect($dto->id)->toBe(12)
        ->and($dto->id)->toBeInt();
});

it('is readonly', function () {
    $data = [
        'id' => 4,
        'name' => 'Finance',
    ];

    $dto = CampaignCategoryDTO::fromArray($data);

    expect(function () use ($dto) {
        $dto->id = 999;
    })->toThrow(Error::class);
});
