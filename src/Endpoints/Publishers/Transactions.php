<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

use Illuminate\Support\Collection;
use JakubOrava\EhubClient\BaseEhubClient;
use JakubOrava\EhubClient\DTO\PaginatedResponse;
use JakubOrava\EhubClient\DTO\Publisher\PublisherTransactionDTO;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;
use JakubOrava\EhubClient\Exceptions\ValidationException;
use JakubOrava\EhubClient\Requests\TransactionsListRequest;

class Transactions
{
    public function __construct(
        private readonly BaseEhubClient $client
    ) {
    }

    /**
     * @return PaginatedResponse<PublisherTransactionDTO>
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(string $userId, TransactionsListRequest|null $request = null): PaginatedResponse
    {
        $request = $request ?? new TransactionsListRequest();
        $queryParams = $request->toArray();

        $response = $this->client->get($userId, 'transactions', $queryParams);

        /** @var array<mixed> $transactionsData */
        $transactionsData = $response['transactions'] ?? [];
        /** @var Collection<int, PublisherTransactionDTO> $transactions */
        $transactions = (new Collection($transactionsData))
            ->map(function (mixed $transaction): PublisherTransactionDTO {
                if (!is_array($transaction)) {
                    throw new UnexpectedResponseException('Expected array for transaction item');
                }
                /** @var array<string, mixed> $transaction */
                return PublisherTransactionDTO::fromArray($transaction);
            });

        $currentPage = $queryParams['page'] ?? null;
        $perPage = $queryParams['perPage'] ?? null;
        $totalItems = $response['totalItems'] ?? 0;
        assert(is_int($totalItems) || is_string($totalItems) || is_float($totalItems));

        return new PaginatedResponse(
            items: $transactions,
            totalItems: is_int($totalItems) ? $totalItems : (int) $totalItems,
            currentPage: $currentPage !== null && is_int($currentPage) ? $currentPage : null,
            perPage: $perPage !== null && is_int($perPage) ? $perPage : null,
        );
    }

    /**
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(string $userId, string $transactionId): PublisherTransactionDTO
    {
        $response = $this->client->get($userId, "transactions/{$transactionId}", []);

        /** @var array<string, mixed> $response */
        return PublisherTransactionDTO::fromArray($response);
    }
}