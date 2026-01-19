<?php

use JakubOrava\EhubClient\DTO\Publisher\CreativeDTO;

it('creates CreativeDTO from array', function () {
    $data = [
        'id' => '0002781B',
        'campaignId' => '0000265X',
        'type' => 'link',
        'name' => 'Default Link',
        'destinationUrl' => 'https://example.com',
        'imageUrl' => 'https://example.com/image.png',
    ];

    $dto = CreativeDTO::fromArray($data);

    expect($dto->id)->toBe('0002781B')
        ->and($dto->campaignId)->toBe('0000265X')
        ->and($dto->type)->toBe('link')
        ->and($dto->name)->toBe('Default Link')
        ->and($dto->destinationUrl)->toBe('https://example.com')
        ->and($dto->imageUrl)->toBe('https://example.com/image.png');
});

it('creates CreativeDTO with null optional fields', function () {
    $data = [
        'id' => '0002781B',
        'campaignId' => '0000265X',
        'type' => 'banner',
        'name' => 'Banner Creative',
        'destinationUrl' => null,
        'imageUrl' => null,
    ];

    $dto = CreativeDTO::fromArray($data);

    expect($dto->destinationUrl)->toBeNull()
        ->and($dto->imageUrl)->toBeNull();
});
