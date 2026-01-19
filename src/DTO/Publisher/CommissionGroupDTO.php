<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Illuminate\Support\Collection;
use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class CommissionGroupDTO
{
    use ArrayHelpers;

    /**
     * @param Collection<int, CommissionDTO> $commissions
     */
    public function __construct(
        public string $name,
        public ?string $status,
        public Collection $commissions,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var Collection<int, CommissionDTO> $commissions */
        $commissions = self::getCollection(
            $data,
            'commissions',
            fn(array $commission): CommissionDTO => CommissionDTO::fromArray($commission)
        );

        return new self(
            name: self::getString($data, 'name'),
            status: self::getStringOrNull($data, 'status'),
            commissions: $commissions,
        );
    }
}
