<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

use Illuminate\Support\Collection;
use JakubOrava\EhubClient\BaseEhubClient;
use JakubOrava\EhubClient\DTO\PaginatedResponse;
use JakubOrava\EhubClient\DTO\Publisher\VoucherDTO;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;
use JakubOrava\EhubClient\Exceptions\ValidationException;
use JakubOrava\EhubClient\Requests\VouchersListRequest;

class Vouchers
{
    public function __construct(
        private readonly BaseEhubClient $client
    ) {
    }

    /**
     * @return PaginatedResponse<VoucherDTO>
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(string $userId, VouchersListRequest|null $request = null): PaginatedResponse
    {
        $request = $request ?? new VouchersListRequest();
        $queryParams = $request->toArray();

        $response = $this->client->get($userId, 'vouchers', $queryParams);

        /** @var array<mixed> $vouchersData */
        $vouchersData = $response['vouchers'] ?? [];
        /** @var Collection<int, VoucherDTO> $vouchers */
        $vouchers = (new Collection($vouchersData))
            ->map(function (mixed $voucher): VoucherDTO {
                if (!is_array($voucher)) {
                    throw new UnexpectedResponseException('Expected array for voucher item');
                }
                /** @var array<string, mixed> $voucher */
                return VoucherDTO::fromArray($voucher);
            });

        $currentPage = $queryParams['page'] ?? null;
        $perPage = $queryParams['perPage'] ?? null;
        $totalItems = $response['totalItems'] ?? 0;
        assert(is_int($totalItems) || is_string($totalItems) || is_float($totalItems));

        return new PaginatedResponse(
            items: $vouchers,
            totalItems: is_int($totalItems) ? $totalItems : (int) $totalItems,
            currentPage: $currentPage !== null && is_int($currentPage) ? $currentPage : null,
            perPage: $perPage !== null && is_int($perPage) ? $perPage : null,
        );
    }
}
