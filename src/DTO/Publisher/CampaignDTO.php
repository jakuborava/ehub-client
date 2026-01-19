<?php

namespace JakubOrava\EhubClient\DTO\Publisher;

use Illuminate\Support\Collection;

readonly class CampaignDTO
{
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
        $categories = collect($data['categories'] ?? [])
            ->map(fn (array $category): CampaignCategoryDTO => CampaignCategoryDTO::fromArray($category));

        $commissionGroups = collect($data['commissionGroups'] ?? [])
            ->map(fn (array $group): CommissionGroupDTO => CommissionGroupDTO::fromArray($group));

        $restrictions = collect($data['restrictions'] ?? [])
            ->map(fn (array $restriction): CampaignRestrictionDTO => CampaignRestrictionDTO::fromArray($restriction));

        return new self(
            id: (string) $data['id'],
            name: (string) $data['name'],
            logoUrl: isset($data['logoUrl']) ? (string) $data['logoUrl'] : null,
            web: isset($data['web']) ? (string) $data['web'] : null,
            country: isset($data['country']) ? (string) $data['country'] : null,
            categories: $categories,
            description: isset($data['description']) ? (string) $data['description'] : null,
            commissionDescription: isset($data['commissionDescription']) ? (string) $data['commissionDescription'] : null,
            commissionGroups: $commissionGroups,
            restrictions: $restrictions,
            averageAmount: isset($data['averageAmount']) ? (float) $data['averageAmount'] : null,
            cookieLifetime: isset($data['cookieLifetime']) ? (int) $data['cookieLifetime'] : null,
            maxApprovalInterval: isset($data['maxApprovalInterval']) ? (int) $data['maxApprovalInterval'] : null,
            tracking: isset($data['tracking']) ? (string) $data['tracking'] : null,
            domainTracking: isset($data['domainTracking']) ? (bool) $data['domainTracking'] : null,
            hasFeed: isset($data['hasFeed']) ? (bool) $data['hasFeed'] : null,
            defaultLink: isset($data['defaultLink']) ? (string) $data['defaultLink'] : null,
        );
    }
}
