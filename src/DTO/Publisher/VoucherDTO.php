<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Carbon\Carbon;
use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class VoucherDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $campaignId,
        public string $campaignName,
        public string $code,
        public string $type,
        public string $rules,
        public string $url,
        public string $destinationUrl,
        public Carbon $validFrom,
        public Carbon $validTill,
        public ?bool $isValid = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getInt($data, 'id'),
            campaignId: self::getString($data, 'campaignId'),
            campaignName: self::getString($data, 'campaignName'),
            code: self::getString($data, 'code'),
            type: self::getString($data, 'type'),
            rules: self::getString($data, 'rules'),
            url: self::getString($data, 'url'),
            destinationUrl: self::getString($data, 'destinationUrl'),
            validFrom: Carbon::parse(self::getString($data, 'validFrom')),
            validTill: Carbon::parse(self::getString($data, 'validTill')),
            isValid: self::getBoolOrNull($data, 'isValid'),
        );
    }
}
