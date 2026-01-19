<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Carbon\Carbon;

readonly class VoucherDTO
{
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
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['id'],
            campaignId: (string) $data['campaignId'],
            campaignName: (string) $data['campaignName'],
            code: (string) $data['code'],
            type: (string) $data['type'],
            rules: (string) $data['rules'],
            url: (string) $data['url'],
            destinationUrl: (string) $data['destinationUrl'],
            validFrom: Carbon::parse($data['validFrom']),
            validTill: Carbon::parse($data['validTill']),
            isValid: isset($data['isValid']) ? (bool) $data['isValid'] : null,
        );
    }
}
