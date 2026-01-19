<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Carbon\Carbon;
use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class OutboundClickDTO
{
    use ArrayHelpers;

    public function __construct(
        public string $id,
        public Carbon $dateTime,
        public string $campaignId,
        public Carbon $clickDateTime,
        public ?string $clickReferrerUrl,
        public ?string $clickData1,
        public ?string $clickData2,
        public ?string $linkId,
        public ?float $commission,
        public ?bool $processed,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getString($data, 'id'),
            dateTime: Carbon::parse(self::getString($data, 'dateTime')),
            campaignId: self::getString($data, 'campaignId'),
            clickDateTime: Carbon::parse(self::getString($data, 'clickDateTime')),
            clickReferrerUrl: self::getStringOrNull($data, 'clickReferrerUrl'),
            clickData1: self::getStringOrNull($data, 'clickData1'),
            clickData2: self::getStringOrNull($data, 'clickData2'),
            linkId: self::getStringOrNull($data, 'linkId'),
            commission: self::getFloatOrNull($data, 'commission'),
            processed: self::getBoolOrNull($data, 'processed'),
        );
    }
}
