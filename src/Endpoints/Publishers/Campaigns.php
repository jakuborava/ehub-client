<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

use Illuminate\Support\Collection;
use JakubOrava\EhubClient\BaseEhubClient;
use JakubOrava\EhubClient\DTO\PaginatedResponse;
use JakubOrava\EhubClient\DTO\Publisher\CampaignDTO;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;
use JakubOrava\EhubClient\Exceptions\ValidationException;
use JakubOrava\EhubClient\Requests\CampaignsListRequest;

class Campaigns
{
    public function __construct(
        private readonly BaseEhubClient $client
    ) {
    }

    /**
     * @return PaginatedResponse<CampaignDTO>
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(string $userId, CampaignsListRequest|null $request = null): PaginatedResponse
    {
        $request = $request ?? new CampaignsListRequest();
        $queryParams = $request->toArray();

        $response = $this->client->get($userId, 'campaigns', $queryParams);

        /** @var array<mixed> $campaignsData */
        $campaignsData = $response['campaigns'] ?? [];
        /** @var Collection<int, CampaignDTO> $campaigns */
        $campaigns = (new Collection($campaignsData))
            ->map(function (mixed $campaign): CampaignDTO {
                if (!is_array($campaign)) {
                    throw new UnexpectedResponseException('Expected array for campaign item');
                }
                /** @var array<string, mixed> $campaign */
                return CampaignDTO::fromArray($campaign);
            });

        $currentPage = $queryParams['page'] ?? null;
        $perPage = $queryParams['perPage'] ?? null;
        $totalItems = $response['totalItems'] ?? 0;
        assert(is_int($totalItems) || is_string($totalItems) || is_float($totalItems));

        return new PaginatedResponse(
            items: $campaigns,
            totalItems: is_int($totalItems) ? $totalItems : (int) $totalItems,
            currentPage: $currentPage !== null && is_int($currentPage) ? $currentPage : null,
            perPage: $perPage !== null && is_int($perPage) ? $perPage : null,
        );
    }
}