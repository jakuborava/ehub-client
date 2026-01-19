<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

use Illuminate\Support\Collection;
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
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(string $userId, CreativesListRequest|null $request = null): PaginatedResponse
    {
        $request = $request ?? new CreativesListRequest();
        $queryParams = $request->toArray();

        $response = $this->client->get($userId, 'creatives', $queryParams);

        /** @var array<mixed> $creativesData */
        $creativesData = $response['creatives'] ?? [];
        /** @var Collection<int, CreativeDTO> $creatives */
        $creatives = (new Collection($creativesData))
            ->map(function (mixed $creative): CreativeDTO {
                if (!is_array($creative)) {
                    throw new UnexpectedResponseException('Expected array for creative item');
                }
                /** @var array<string, mixed> $creative */
                return CreativeDTO::fromArray($creative);
            });

        $currentPage = $queryParams['page'] ?? null;
        $perPage = $queryParams['perPage'] ?? null;
        $totalItems = $response['totalItems'] ?? 0;
        assert(is_int($totalItems) || is_string($totalItems) || is_float($totalItems));

        return new PaginatedResponse(
            items: $creatives,
            totalItems: is_int($totalItems) ? $totalItems : (int) $totalItems,
            currentPage: $currentPage !== null && is_int($currentPage) ? $currentPage : null,
            perPage: $perPage !== null && is_int($perPage) ? $perPage : null,
        );
    }
}