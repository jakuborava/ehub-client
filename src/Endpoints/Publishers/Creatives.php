<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

use JakubOrava\EhubClient\BaseEhubClient;
use JakubOrava\EhubClient\DTO\PaginatedResponse;
use JakubOrava\EhubClient\DTO\Publisher\CreativeDTO;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;
use JakubOrava\EhubClient\Exceptions\ValidationException;
use JakubOrava\EhubClient\Requests\CreativesListRequest;

class Creatives
{
    public function __construct(
        private readonly BaseEhubClient $client
    ) {
    }

    /**
     * @return PaginatedResponse<CreativeDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(string $userId, ?CreativesListRequest $request = null): PaginatedResponse
    {
        $request = $request ?? new CreativesListRequest;
        $queryParams = $request->toArray();

        $response = $this->client->get($userId, 'creatives', $queryParams);
        /** @var array<string, mixed> $response */

        return PaginatedResponse::fromResponse(
            response: $response,
            queryParams: $queryParams,
            itemsKey: 'creatives',
            mapper: fn(array $creative): CreativeDTO => CreativeDTO::fromArray($creative)
        );
    }
}
