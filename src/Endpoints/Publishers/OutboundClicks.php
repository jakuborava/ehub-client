<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

use Illuminate\Support\Collection;
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
    ) {
    }

    /**
     * @return PaginatedResponse<OutboundClickDTO>
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(string $userId, OutboundClicksListRequest|null $request = null): PaginatedResponse
    {
        $request = $request ?? new OutboundClicksListRequest();
        $queryParams = $request->toArray();

        $response = $this->client->get($userId, 'outboundClicks', $queryParams);

        /** @var array<mixed> $outboundClicksData */
        $outboundClicksData = $response['outboundClicks'] ?? [];
        /** @var Collection<int, OutboundClickDTO> $outboundClicks */
        $outboundClicks = (new Collection($outboundClicksData))
            ->map(function (mixed $click): OutboundClickDTO {
                if (!is_array($click)) {
                    throw new UnexpectedResponseException('Expected array for outbound click item');
                }
                /** @var array<string, mixed> $click */
                return OutboundClickDTO::fromArray($click);
            });

        $currentPage = $queryParams['page'] ?? null;
        $perPage = $queryParams['perPage'] ?? null;
        $totalItems = $response['totalItems'] ?? 0;
        assert(is_int($totalItems) || is_string($totalItems) || is_float($totalItems));

        return new PaginatedResponse(
            items: $outboundClicks,
            totalItems: is_int($totalItems) ? $totalItems : (int) $totalItems,
            currentPage: $currentPage !== null && is_int($currentPage) ? $currentPage : null,
            perPage: $perPage !== null && is_int($perPage) ? $perPage : null,
        );
    }
}