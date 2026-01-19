<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

readonly class CommissionDTO
{
    public function __construct(
        public string $commissionType,
        public ?string $name,
        public string $valueType,
        public float $value,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            commissionType: (string) $data['commissionType'],
            name: isset($data['name']) ? (string) $data['name'] : null,
            valueType: (string) $data['valueType'],
            value: (float) $data['value'],
        );
    }
}
