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

        return (int) ceil($this->totalItems / $this->perPage);
    }

    public function hasMorePages(): bool
    {
        if ($this->currentPage === null || $this->getTotalPages() === null) {
            return false;
        }

        return $this->currentPage < $this->getTotalPages();
    }
}
