<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

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

        $vouchers = collect($response['vouchers'] ?? [])
            ->map(fn (array $voucher): VoucherDTO => VoucherDTO::fromArray($voucher));

        return new PaginatedResponse(
            items: $vouchers,
            totalItems: (int) ($response['totalItems'] ?? 0),
            currentPage: $queryParams['page'] ?? null,
            perPage: $queryParams['perPage'] ?? null,
        );
    }
}
