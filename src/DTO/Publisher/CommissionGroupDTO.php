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
        $commissionsData = self::getArray($data, 'commissions');
        /** @var Collection<int, CommissionDTO> $commissions */
        $commissions = (new Collection($commissionsData))
            ->map(function (mixed $commission): CommissionDTO {
                if (!is_array($commission)) {
                    throw new \InvalidArgumentException('Expected array for commission item');
                }
                /** @var array<string, mixed> $commission */
                return CommissionDTO::fromArray($commission);
            });

        return new self(
            name: self::getString($data, 'name'),
            status: self::getStringOrNull($data, 'status'),
            commissions: $commissions,
        );
    }
}
