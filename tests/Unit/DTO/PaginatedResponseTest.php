<?php

use Illuminate\Support\Collection;
use JakubOrava\EhubClient\DTO\PaginatedResponse;

it('calculates total pages correctly', function () {
    $items = new Collection;
    $response = new PaginatedResponse(
        items: $items,
        totalItems: 100,
        currentPage: 1,
        perPage: 25
    );

    expect($response->getTotalPages())->toBe(4);
});

it('returns null for total pages when perPage is null', function () {
    $items = new Collection;
    $response = new PaginatedResponse(
        items: $items,
        totalItems: 100,
        currentPage: 1,
        perPage: null
    );

    expect($response->getTotalPages())->toBeNull();
});

it('correctly identifies when there are more pages', function () {
    $items = new Collection;
    $response = new PaginatedResponse(
        items: $items,
        totalItems: 100,
        currentPage: 2,
        perPage: 25
    );

    expect($response->hasMorePages())->toBeTrue();
});

it('correctly identifies when there are no more pages', function () {
    $items = new Collection;
    $response = new PaginatedResponse(
        items: $items,
        totalItems: 100,
        currentPage: 4,
        perPage: 25
    );

    expect($response->hasMorePages())->toBeFalse();
});

it('returns false for hasMorePages when pagination info is missing', function () {
    $items = new Collection;
    $response = new PaginatedResponse(
        items: $items,
        totalItems: 100,
        currentPage: null,
        perPage: null
    );

    expect($response->hasMorePages())->toBeFalse();
});
