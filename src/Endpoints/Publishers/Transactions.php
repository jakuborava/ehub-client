<?php

namespace JakubOrava\EhubClient\Endpoints\Publishers;

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

        $transactions = collect($response['transactions'] ?? [])
            ->map(fn (array $transaction): PublisherTransactionDTO => PublisherTransactionDTO::fromArray($transaction));

        return new PaginatedResponse(
            items: $transactions,
            totalItems: (int) ($response['totalItems'] ?? 0),
            currentPage: $queryParams['page'] ?? null,
            perPage: $queryParams['perPage'] ?? null,
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

        return PublisherTransactionDTO::fromArray($response);
    }
}