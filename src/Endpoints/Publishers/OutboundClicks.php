<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

use JakubOrava\EhubClient\BaseEhubClient;
use JakubOrava\EhubClient\DTO\PaginatedResponse;
use JakubOrava\EhubClient\DTO\Publisher\OutboundClickDTO;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;
use JakubOrava\EhubClient\Exceptions\ValidationException;
use JakubOrava\EhubClient\Requests\OutboundClicksListRequest;

class OutboundClicks
{
    public function __construct(
        private readonly BaseEhubClient $client
    ) {}

    /**
     * @return PaginatedResponse<OutboundClickDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(string $userId, ?OutboundClicksListRequest $request = null): PaginatedResponse
    {
        $request = $request ?? new OutboundClicksListRequest;
        $queryParams = $request->toArray();

        $response = $this->client->get($userId, 'outboundClicks', $queryParams);
        /** @var array<string, mixed> $response */

        return PaginatedResponse::fromResponse(
            response: $response,
            queryParams: $queryParams,
            itemsKey: 'outboundClicks',
            mapper: fn (array $click): OutboundClickDTO => OutboundClickDTO::fromArray($click)
        );
    }
}
