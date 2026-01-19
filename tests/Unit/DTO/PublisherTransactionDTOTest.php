<?php

use JakubOrava\EhubClient\DTO\Publisher\PublisherTransactionDTO;

it('creates PublisherTransactionDTO from array', function () {
    $data = [
        'id' => 'b49feb8a',
        'uuid' => '36130a28-9ade-49c0-9560-6a36ea1a64cd',
        'dateInserted' => '2024-01-24T14:52:07+0100',
        'campaignId' => '654ba4aa',
        'creativeId' => '4eb23ba6',
        'type' => 'PPS',
        'code' => 'special',
        'commission' => 100.0,
        'amount' => 1000.0,
        'ip' => '1.2.3.4',
        'clickCount' => 2,
        'firstClickDateTime' => '2024-01-24T14:52:07+0100',
        'firstClickIp' => '1.2.3.4',
        'firstClickData1' => 'data1',
        'firstClickData2' => 'data2',
        'lastClickDateTime' => '2024-01-24T15:54:01+0100',
        'lastClickIp' => '1.2.4.5',
        'lastClickData1' => 'data1',
        'lastClickData2' => 'data2',
        'lastClickReferrerUrl' => 'https://publisher.cz/link',
        'orderId' => 'ORD123',
        'productId' => 'P456',
        'newCustomer' => true,
        'resolutionDateTime' => '2024-01-25T10:00:00+0100',
        'status' => 'pending',
        'payoutStatus' => 'unpaid',
    ];

    $dto = PublisherTransactionDTO::fromArray($data);

    expect($dto->id)->toBe('b49feb8a')
        ->and($dto->uuid)->toBe('36130a28-9ade-49c0-9560-6a36ea1a64cd')
        ->and($dto->dateInserted->format('Y-m-d\TH:i:sO'))->toBe('2024-01-24T14:52:07+0100')
        ->and($dto->type)->toBe('PPS')
        ->and($dto->commission)->toBe(100.0)
        ->and($dto->amount)->toBe(1000.0)
        ->and($dto->clickCount)->toBe(2)
        ->and($dto->newCustomer)->toBeTrue()
        ->and($dto->status)->toBe('pending')
        ->and($dto->payoutStatus)->toBe('unpaid');
});

it('creates PublisherTransactionDTO with null optional fields', function () {
    $data = [
        'id' => 'b49feb8a',
        'uuid' => '36130a28-9ade-49c0-9560-6a36ea1a64cd',
        'dateInserted' => '2024-01-24T14:52:07+0100',
        'campaignId' => '654ba4aa',
        'creativeId' => '4eb23ba6',
        'type' => 'PPS',
        'code' => null,
        'commission' => 100.0,
        'amount' => 1000.0,
        'ip' => null,
        'clickCount' => null,
        'firstClickDateTime' => null,
        'firstClickIp' => null,
        'firstClickData1' => null,
        'firstClickData2' => null,
        'lastClickDateTime' => null,
        'lastClickIp' => null,
        'lastClickData1' => null,
        'lastClickData2' => null,
        'lastClickReferrerUrl' => null,
        'orderId' => null,
        'productId' => null,
        'newCustomer' => null,
        'resolutionDateTime' => null,
        'status' => 'pending',
        'payoutStatus' => 'unpaid',
    ];

    $dto = PublisherTransactionDTO::fromArray($data);

    expect($dto->code)->toBeNull()
        ->and($dto->orderId)->toBeNull()
        ->and($dto->productId)->toBeNull()
        ->and($dto->newCustomer)->toBeNull();
});
