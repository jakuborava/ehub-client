<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

readonly class CreativeDTO
{
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
            id: (string) $data['id'],
            campaignId: (string) $data['campaignId'],
            type: (string) $data['type'],
            name: (string) $data['name'],
            destinationUrl: isset($data['destinationUrl']) ? (string) $data['destinationUrl'] : null,
            imageUrl: isset($data['imageUrl']) ? (string) $data['imageUrl'] : null,
        );
    }
}
