<?php

use JakubOrava\EhubClient\DTO\Publisher\OutboundClickDTO;

it('creates OutboundClickDTO from array', function () {
    $data = [
        'id' => 'd0c75e9d-300b-4cf7-849b-c56db45d76b7',
        'dateTime' => '2023-06-10T17:11:33+0100',
        'campaignId' => '654ba4aa',
        'clickDateTime' => '2023-06-10T17:05:45+0100',
        'clickReferrerUrl' => 'https://publisher.com',
        'clickData1' => 'some_data',
        'clickData2' => 'other_data',
        'linkId' => 'outbound_click_01',
        'commission' => 1.5,
        'processed' => true,
    ];

    $dto = OutboundClickDTO::fromArray($data);

    expect($dto->id)->toBe('d0c75e9d-300b-4cf7-849b-c56db45d76b7')
        ->and($dto->dateTime->format('Y-m-d\TH:i:sO'))->toBe('2023-06-10T17:11:33+0100')
        ->and($dto->campaignId)->toBe('654ba4aa')
        ->and($dto->clickDateTime->format('Y-m-d\TH:i:sO'))->toBe('2023-06-10T17:05:45+0100')
        ->and($dto->clickReferrerUrl)->toBe('https://publisher.com')
        ->and($dto->clickData1)->toBe('some_data')
        ->and($dto->clickData2)->toBe('other_data')
        ->and($dto->linkId)->toBe('outbound_click_01')
        ->and($dto->commission)->toBe(1.5)
        ->and($dto->processed)->toBeTrue();
});

it('creates OutboundClickDTO with null optional fields', function () {
    $data = [
        'id' => 'd0c75e9d-300b-4cf7-849b-c56db45d76b7',
        'dateTime' => '2023-06-10T17:11:33+0100',
        'campaignId' => '654ba4aa',
        'clickDateTime' => '2023-06-10T17:05:45+0100',
        'clickReferrerUrl' => null,
        'clickData1' => null,
        'clickData2' => null,
        'linkId' => null,
        'commission' => null,
        'processed' => null,
    ];

    $dto = OutboundClickDTO::fromArray($data);

    expect($dto->clickReferrerUrl)->toBeNull()
        ->and($dto->clickData1)->toBeNull()
        ->and($dto->clickData2)->toBeNull()
        ->and($dto->linkId)->toBeNull()
        ->and($dto->commission)->toBeNull()
        ->and($dto->processed)->toBeNull();
});
