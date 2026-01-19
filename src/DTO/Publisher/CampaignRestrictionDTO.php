<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class CampaignRestrictionDTO
{
    use ArrayHelpers;

    public function __construct(
        public string $name,
        public string $description,
        public ?string $note,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: self::getString($data, 'name'),
            description: self::getString($data, 'description'),
            note: self::getStringOrNull($data, 'note'),
        );
    }
}
