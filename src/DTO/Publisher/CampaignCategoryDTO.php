<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

readonly class CampaignCategoryDTO
{
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
            code: (int) $data['code'],
            name: (string) $data['name'],
        );
    }
}
