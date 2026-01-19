<?php

namespace JakubOrava\EhubClient\DTO;

use Illuminate\Support\Collection;

/**
 * @template T
 */
readonly class PaginatedResponse
{
    /**
     * @param Collection<int, T> $items
     */
    public function __construct(
        public Collection $items,
        public int $totalItems,
        public ?int $currentPage = null,
        public ?int $perPage = null,
    ) {
    }

    public function getTotalPages(): ?int
    {
        if ($this->perPage === null || $this->perPage === 0) {
            return null;
        }

        return (int)ceil($this->totalItems / $this->perPage);
    }

    public function hasMorePages(): bool
    {
        if ($this->currentPage === null || $this->getTotalPages() === null) {
            return false;
        }

        return $this->currentPage < $this->getTotalPages();
    }

    /**
     * Create a paginated response from API response data
     *
     * @template TItem
     *
     * @param array<string, mixed> $response
     * @param array<string, mixed> $queryParams
     * @param callable(array<string, mixed>): TItem $mapper
     * @return self<TItem>
     */
    public static function fromResponse(array $response, array $queryParams, string $itemsKey, callable $mapper): self
    {
        /** @var array<mixed> $itemsData */
        $itemsData = $response[$itemsKey] ?? [];

        /** @var Collection<int, TItem> $items */
        $items = new Collection($itemsData)
            ->map(function (mixed $item) use ($mapper): mixed {
                if (!is_array($item)) {
                    throw new \InvalidArgumentException('Expected array for item in response');
                }

                /** @var array<string, mixed> $item */
                return $mapper($item);
            });

        $totalItems = $response['totalItems'] ?? 0;
        if (!is_int($totalItems) && !is_numeric($totalItems)) {
            throw new \InvalidArgumentException('Expected int or numeric for totalItems');
        }

        $currentPage = $queryParams['page'] ?? null;
        $perPage = $queryParams['perPage'] ?? null;

        return new self(
            items: $items,
            totalItems: is_int($totalItems) ? $totalItems : (int)$totalItems,
            currentPage: $currentPage !== null && is_int($currentPage) ? $currentPage : null,
            perPage: $perPage !== null && is_int($perPage) ? $perPage : null,
        );
    }
}
