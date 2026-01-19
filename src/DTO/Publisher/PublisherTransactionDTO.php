<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Carbon\Carbon;

readonly class PublisherTransactionDTO
{
    public function __construct(
        public string $id,
        public string $uuid,
        public Carbon $dateInserted,
        public string $campaignId,
        public string $creativeId,
        public string $type,
        public ?string $code,
        public float $commission,
        public float $amount,
        public ?string $ip,
        public ?int $clickCount,
        public ?Carbon $firstClickDateTime,
        public ?string $firstClickIp,
        public ?string $firstClickData1,
        public ?string $firstClickData2,
        public ?Carbon $lastClickDateTime,
        public ?string $lastClickIp,
        public ?string $lastClickData1,
        public ?string $lastClickData2,
        public ?string $lastClickReferrerUrl,
        public ?string $orderId,
        public ?string $productId,
        public ?bool $newCustomer,
        public ?Carbon $resolutionDateTime,
        public string $status,
        public string $payoutStatus,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            uuid: (string) $data['uuid'],
            dateInserted: Carbon::parse($data['dateInserted']),
            campaignId: (string) $data['campaignId'],
            creativeId: (string) $data['creativeId'],
            type: (string) $data['type'],
            code: isset($data['code']) ? (string) $data['code'] : null,
            commission: (float) $data['commission'],
            amount: (float) $data['amount'],
            ip: isset($data['ip']) ? (string) $data['ip'] : null,
            clickCount: isset($data['clickCount']) ? (int) $data['clickCount'] : null,
            firstClickDateTime: isset($data['firstClickDateTime']) ? Carbon::parse($data['firstClickDateTime']) : null,
            firstClickIp: isset($data['firstClickIp']) ? (string) $data['firstClickIp'] : null,
            firstClickData1: isset($data['firstClickData1']) ? (string) $data['firstClickData1'] : null,
            firstClickData2: isset($data['firstClickData2']) ? (string) $data['firstClickData2'] : null,
            lastClickDateTime: isset($data['lastClickDateTime']) ? Carbon::parse($data['lastClickDateTime']) : null,
            lastClickIp: isset($data['lastClickIp']) ? (string) $data['lastClickIp'] : null,
            lastClickData1: isset($data['lastClickData1']) ? (string) $data['lastClickData1'] : null,
            lastClickData2: isset($data['lastClickData2']) ? (string) $data['lastClickData2'] : null,
            lastClickReferrerUrl: isset($data['lastClickReferrerUrl']) ? (string) $data['lastClickReferrerUrl'] : null,
            orderId: isset($data['orderId']) ? (string) $data['orderId'] : null,
            productId: isset($data['productId']) ? (string) $data['productId'] : null,
            newCustomer: isset($data['newCustomer']) ? (bool) $data['newCustomer'] : null,
            resolutionDateTime: isset($data['resolutionDateTime']) ? Carbon::parse($data['resolutionDateTime']) : null,
            status: (string) $data['status'],
            payoutStatus: (string) $data['payoutStatus'],
        );
    }
}
