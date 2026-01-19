<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

readonly class CampaignRestrictionDTO
{
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
            name: (string) $data['name'],
            description: (string) $data['description'],
            note: isset($data['note']) ? (string) $data['note'] : null,
        );
    }
}
