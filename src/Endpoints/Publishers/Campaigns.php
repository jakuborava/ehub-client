<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

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
    ) {}

    /**
     * @return PaginatedResponse<CampaignDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(string $userId, ?CampaignsListRequest $request = null): PaginatedResponse
    {
        $request = $request ?? new CampaignsListRequest;
        $queryParams = $request->toArray();

        $response = $this->client->get($userId, 'campaigns', $queryParams);
        /** @var array<string, mixed> $response */

        return PaginatedResponse::fromResponse(
            response: $response,
            queryParams: $queryParams,
            itemsKey: 'campaigns',
            mapper: fn (array $campaign): CampaignDTO => CampaignDTO::fromArray($campaign)
        );
    }
}
