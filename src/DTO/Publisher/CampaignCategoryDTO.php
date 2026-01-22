<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class CampaignCategoryDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $name,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getInt($data, 'id'),
            name: self::getString($data, 'name'),
        );
    }
}
