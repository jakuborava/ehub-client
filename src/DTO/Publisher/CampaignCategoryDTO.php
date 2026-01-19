<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class CampaignCategoryDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $code,
        public string $name,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            code: self::getInt($data, 'code'),
            name: self::getString($data, 'name'),
        );
    }
}
