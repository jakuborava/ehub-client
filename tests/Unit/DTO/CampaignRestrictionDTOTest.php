<?php

use JakubOrava\EhubClient\DTO\Publisher\CampaignRestrictionDTO;

it('creates CampaignRestrictionDTO from array with all fields', function () {
    $data = [
        'name' => 'No PPC',
        'description' => 'PPC advertising is forbidden',
        'note' => 'Violation may result in termination',
    ];

    $dto = CampaignRestrictionDTO::fromArray($data);

    expect($dto->name)->toBe('No PPC')
        ->and($dto->description)->toBe('PPC advertising is forbidden')
        ->and($dto->note)->toBe('Violation may result in termination');
});

it('creates CampaignRestrictionDTO with null note', function () {
    $data = [
        'name' => 'Brand Bidding',
        'description' => 'Bidding on brand terms is not allowed',
        'note' => null,
    ];

    $dto = CampaignRestrictionDTO::fromArray($data);

    expect($dto->name)->toBe('Brand Bidding')
        ->and($dto->description)->toBe('Bidding on brand terms is not allowed')
        ->and($dto->note)->toBeNull();
});

it('creates CampaignRestrictionDTO with empty description', function () {
    $data = [
        'name' => 'Age Restriction',
        'description' => '',
        'note' => 'Adults only',
    ];

    $dto = CampaignRestrictionDTO::fromArray($data);

    expect($dto->name)->toBe('Age Restriction')
        ->and($dto->description)->toBe('')
        ->and($dto->note)->toBe('Adults only');
});

it('creates CampaignRestrictionDTO with null note only', function () {
    $data = [
        'name' => 'Custom Restriction',
        'description' => 'Some description',
        'note' => null,
    ];

    $dto = CampaignRestrictionDTO::fromArray($data);

    expect($dto->name)->toBe('Custom Restriction')
        ->and($dto->description)->toBe('Some description')
        ->and($dto->note)->toBeNull();
});

it('creates CampaignRestrictionDTO with long description', function () {
    $data = [
        'name' => 'Content Guidelines',
        'description' => 'Publishers must follow all content guidelines including but not limited to appropriate language, ethical promotion, and compliance with local regulations.',
        'note' => 'See full guidelines document',
    ];

    $dto = CampaignRestrictionDTO::fromArray($data);

    expect($dto->name)->toBe('Content Guidelines')
        ->and($dto->description)->toContain('compliance with local regulations')
        ->and($dto->note)->toBe('See full guidelines document');
});

it('creates CampaignRestrictionDTO with special characters', function () {
    $data = [
        'name' => 'Email Marketing & Newsletter',
        'description' => 'Use of email marketing requires prior approval',
        'note' => 'Contact support@example.com for approval',
    ];

    $dto = CampaignRestrictionDTO::fromArray($data);

    expect($dto->name)->toBe('Email Marketing & Newsletter')
        ->and($dto->description)->toBe('Use of email marketing requires prior approval')
        ->and($dto->note)->toContain('support@example.com');
});

it('is readonly', function () {
    $data = [
        'name' => 'No PPC',
        'description' => 'PPC forbidden',
        'note' => 'Some note',
    ];

    $dto = CampaignRestrictionDTO::fromArray($data);

    expect(function () use ($dto) {
        $dto->name = 'Changed';
    })->toThrow(Error::class);
});
