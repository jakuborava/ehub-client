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
        $categoriesData = self::getArray($data, 'categories');
        /** @var Collection<int, CampaignCategoryDTO> $categories */
        $categories = (new Collection($categoriesData))
            ->map(function (mixed $category): CampaignCategoryDTO {
                if (!is_array($category)) {
                    throw new \InvalidArgumentException('Expected array for category item');
                }
                /** @var array<string, mixed> $category */
                return CampaignCategoryDTO::fromArray($category);
            });

        $commissionGroupsData = self::getArray($data, 'commissionGroups');
        /** @var Collection<int, CommissionGroupDTO> $commissionGroups */
        $commissionGroups = (new Collection($commissionGroupsData))
            ->map(function (mixed $group): CommissionGroupDTO {
                if (!is_array($group)) {
                    throw new \InvalidArgumentException('Expected array for commission group item');
                }
                /** @var array<string, mixed> $group */
                return CommissionGroupDTO::fromArray($group);
            });

        $restrictionsData = self::getArray($data, 'restrictions');
        /** @var Collection<int, CampaignRestrictionDTO> $restrictions */
        $restrictions = (new Collection($restrictionsData))
            ->map(function (mixed $restriction): CampaignRestrictionDTO {
                if (!is_array($restriction)) {
                    throw new \InvalidArgumentException('Expected array for restriction item');
                }
                /** @var array<string, mixed> $restriction */
                return CampaignRestrictionDTO::fromArray($restriction);
            });

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
