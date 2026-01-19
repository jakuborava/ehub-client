<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Illuminate\Support\Collection;

readonly class CommissionGroupDTO
{
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
        $commissions = collect($data['commissions'] ?? [])
            ->map(fn (array $commission): CommissionDTO => CommissionDTO::fromArray($commission));

        return new self(
            name: (string) $data['name'],
            status: isset($data['status']) ? (string) $data['status'] : null,
            commissions: $commissions,
        );
    }
}
