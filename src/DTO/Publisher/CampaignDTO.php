<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Illuminate\Support\Collection;
use JakubOrava\EhubClient\DTO\ArrayHelpers;

readonly class CampaignDTO
{
    use ArrayHelpers;

    /**
     * @param Collection<int, CampaignCategoryDTO> $categories
     * @param Collection<int, CommissionGroupDTO> $commissionGroups
     * @param Collection<int, CampaignRestrictionDTO> $restrictions
     */
    public function __construct(
        public string $id,
        public string $name,
        public ?string $logoUrl,
        public ?string $web,
        public ?string $country,
        public Collection $categories,
        public ?string $description,
        public ?string $commissionDescription,
        public Collection $commissionGroups,
        public Collection $restrictions,
        public ?float $averageAmount,
        public ?int $cookieLifetime,
        public ?int $maxApprovalInterval,
        public ?string $tracking,
        public ?bool $domainTracking,
        public ?bool $hasFeed,
        public ?string $defaultLink,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var Collection<int, CampaignCategoryDTO> $categories */
        $categories = self::getCollection(
            $data,
            'categories',
            fn(array $category): CampaignCategoryDTO => CampaignCategoryDTO::fromArray($category)
        );

        /** @var Collection<int, CommissionGroupDTO> $commissionGroups */
        $commissionGroups = self::getCollection(
            $data,
            'commissionGroups',
            fn(array $group): CommissionGroupDTO => CommissionGroupDTO::fromArray($group)
        );

        /** @var Collection<int, CampaignRestrictionDTO> $restrictions */
        $restrictions = self::getCollection(
            $data,
            'restrictions',
            fn(array $restriction): CampaignRestrictionDTO => CampaignRestrictionDTO::fromArray($restriction)
        );

        return new self(
            id: self::getString($data, 'id'),
            name: self::getString($data, 'name'),
            logoUrl: self::getStringOrNull($data, 'logoUrl'),
            web: self::getStringOrNull($data, 'web'),
            country: self::getStringOrNull($data, 'country'),
            categories: $categories,
            description: self::getStringOrNull($data, 'description'),
            commissionDescription: self::getStringOrNull($data, 'commissionDescription'),
            commissionGroups: $commissionGroups,
            restrictions: $restrictions,
            averageAmount: self::getFloatOrNull($data, 'averageAmount'),
            cookieLifetime: self::getIntOrNull($data, 'cookieLifetime'),
            maxApprovalInterval: self::getIntOrNull($data, 'maxApprovalInterval'),
            tracking: self::getStringOrNull($data, 'tracking'),
            domainTracking: self::getBoolOrNull($data, 'domainTracking'),
            hasFeed: self::getBoolOrNull($data, 'hasFeed'),
            defaultLink: self::getStringOrNull($data, 'defaultLink'),
        );
    }
}
