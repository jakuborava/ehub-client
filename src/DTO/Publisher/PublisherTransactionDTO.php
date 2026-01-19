<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Carbon\Carbon;
use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class PublisherTransactionDTO
{
    use ArrayHelpers;

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
            id: self::getString($data, 'id'),
            uuid: self::getString($data, 'uuid'),
            dateInserted: Carbon::parse(self::getString($data, 'dateInserted')),
            campaignId: self::getString($data, 'campaignId'),
            creativeId: self::getString($data, 'creativeId'),
            type: self::getString($data, 'type'),
            code: self::getStringOrNull($data, 'code'),
            commission: self::getFloat($data, 'commission'),
            amount: self::getFloat($data, 'amount'),
            ip: self::getStringOrNull($data, 'ip'),
            clickCount: self::getIntOrNull($data, 'clickCount'),
            firstClickDateTime: self::getCarbonOrNull($data, 'firstClickDateTime'),
            firstClickIp: self::getStringOrNull($data, 'firstClickIp'),
            firstClickData1: self::getStringOrNull($data, 'firstClickData1'),
            firstClickData2: self::getStringOrNull($data, 'firstClickData2'),
            lastClickDateTime: self::getCarbonOrNull($data, 'lastClickDateTime'),
            lastClickIp: self::getStringOrNull($data, 'lastClickIp'),
            lastClickData1: self::getStringOrNull($data, 'lastClickData1'),
            lastClickData2: self::getStringOrNull($data, 'lastClickData2'),
            lastClickReferrerUrl: self::getStringOrNull($data, 'lastClickReferrerUrl'),
            orderId: self::getStringOrNull($data, 'orderId'),
            productId: self::getStringOrNull($data, 'productId'),
            newCustomer: self::getBoolOrNull($data, 'newCustomer'),
            resolutionDateTime: self::getCarbonOrNull($data, 'resolutionDateTime'),
            status: self::getString($data, 'status'),
            payoutStatus: self::getString($data, 'payoutStatus'),
        );
    }
}
