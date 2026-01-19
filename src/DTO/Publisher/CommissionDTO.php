<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class CommissionDTO
{
    use ArrayHelpers;
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
            commissionType: self::getString($data, 'commissionType'),
            name: self::getStringOrNull($data, 'name'),
            valueType: self::getString($data, 'valueType'),
            value: self::getFloat($data, 'value'),
        );
    }
}
