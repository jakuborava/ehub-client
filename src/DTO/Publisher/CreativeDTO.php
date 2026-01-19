<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class CreativeDTO
{
    use ArrayHelpers;
    public function __construct(
        public string $id,
        public string $campaignId,
        public string $type,
        public string $name,
        public ?string $destinationUrl,
        public ?string $imageUrl = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getString($data, 'id'),
            campaignId: self::getString($data, 'campaignId'),
            type: self::getString($data, 'type'),
            name: self::getString($data, 'name'),
            destinationUrl: self::getStringOrNull($data, 'destinationUrl'),
            imageUrl: self::getStringOrNull($data, 'imageUrl'),
        );
    }
}
