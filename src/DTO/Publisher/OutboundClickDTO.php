<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Carbon\Carbon;

readonly class OutboundClickDTO
{
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
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            dateTime: Carbon::parse($data['dateTime']),
            campaignId: (string) $data['campaignId'],
            clickDateTime: Carbon::parse($data['clickDateTime']),
            clickReferrerUrl: isset($data['clickReferrerUrl']) ? (string) $data['clickReferrerUrl'] : null,
            clickData1: isset($data['clickData1']) ? (string) $data['clickData1'] : null,
            clickData2: isset($data['clickData2']) ? (string) $data['clickData2'] : null,
            linkId: isset($data['linkId']) ? (string) $data['linkId'] : null,
            commission: isset($data['commission']) ? (float) $data['commission'] : null,
            processed: isset($data['processed']) ? (bool) $data['processed'] : null,
        );
    }
}
