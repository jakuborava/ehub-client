# eHub Laravel Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jakuborava/ehub-client.svg?style=flat-square)](https://packagist.org/packages/jakuborava/ehub-client)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jakuborava/ehub-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jakuborava/ehub-client/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jakuborava/ehub-client/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jakuborava/ehub-client/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jakuborava/ehub-client.svg?style=flat-square)](https://packagist.org/packages/jakuborava/ehub-client)

A comprehensive Laravel package for interacting with the eHub Affiliate Network API. This package provides a fluent, type-safe interface for all Publisher endpoints with full DTO support, request objects, and comprehensive error handling.

## Features

- ✅ Full support for all Publisher endpoints
- ✅ Type-safe DTOs for all responses
- ✅ Fluent Request objects for building queries
- ✅ Pagination support with metadata
- ✅ Comprehensive exception hierarchy
- ✅ PHP 8.4+ with strict typing
- ✅ PHPStan Level MAX compliant
- ✅ Laravel HTTP Client integration
- ✅ Easy userId switching per request

## Installation

You can install the package via composer:

```bash
composer require jakuborava/ehub-client
```

Publish the config file:

```bash
php artisan vendor:publish --tag="ehub-client-config"
```

This is the contents of the published config file:

```php
return [
    'api_key' => env('EHUB_API_KEY'),
];
```

Add your eHub API key to your `.env` file:

```env
EHUB_API_KEY=your-api-key-here
```

## Usage

### Basic Usage

The client uses a fluent, hierarchical API design. All methods require a `userId` (publisher ID) to be passed per request, allowing you to easily switch between different publisher accounts.

```php
use JakubOrava\EhubClient\EhubClient;

$client = new EhubClient();
$publisherId = 'your-publisher-id';

// Get vouchers
$vouchers = $client->publishers()
    ->vouchers()
    ->list($publisherId);

// Access the results
foreach ($vouchers->items as $voucher) {
    echo $voucher->code;
    echo $voucher->campaignName;
    echo $voucher->url;
}

// Pagination info
echo $vouchers->totalItems;      // Total number of items
echo $vouchers->currentPage;     // Current page number
echo $vouchers->perPage;         // Items per page
echo $vouchers->getTotalPages(); // Total pages
echo $vouchers->hasMorePages();  // Check if more pages exist
```

### Using Request Objects

Request objects provide a fluent interface for building queries with pagination, filtering, and sorting:

```php
use JakubOrava\EhubClient\Requests\VouchersListRequest;

$request = (new VouchersListRequest())
    ->page(2)
    ->perPage(100)
    ->type('voucher')
    ->isValid(true)
    ->sort('-validFrom')
    ->fields(['id', 'code', 'campaignName', 'url']);

$vouchers = $client->publishers()
    ->vouchers()
    ->list($publisherId, $request);
```

### Switching Between Users

Since `userId` is passed per request, you can easily switch between different publisher accounts:

```php
$publisher1Vouchers = $client->publishers()
    ->vouchers()
    ->list('publisher-1-id');

$publisher2Vouchers = $client->publishers()
    ->vouchers()
    ->list('publisher-2-id');
```

## Available Endpoints

### Campaigns

List all campaigns accessible to the publisher:

```php
use JakubOrava\EhubClient\Requests\CampaignsListRequest;

$request = (new CampaignsListRequest())
    ->page(1)
    ->perPage(50)
    ->name('Campaign Name')
    ->categories([4, 6]) // Finance and E-shops
    ->status('approved')
    ->sort('name');

$campaigns = $client->publishers()
    ->campaigns()
    ->list($publisherId, $request);

foreach ($campaigns->items as $campaign) {
    echo $campaign->id;
    echo $campaign->name;
    echo $campaign->web;
    echo $campaign->cookieLifetime;

    // Access nested collections
    foreach ($campaign->categories as $category) {
        echo $category->name;
    }

    foreach ($campaign->commissionGroups as $group) {
        echo $group->name;
        foreach ($group->commissions as $commission) {
            echo $commission->value . $commission->valueType;
        }
    }
}
```

### Creatives

List all creatives (banners, links, etc.) for the publisher:

```php
use JakubOrava\EhubClient\Requests\CreativesListRequest;

$request = (new CreativesListRequest())
    ->campaignId('campaign-id')
    ->type('link')
    ->name('Default link')
    ->sort('-id');

$creatives = $client->publishers()
    ->creatives()
    ->list($publisherId, $request);

foreach ($creatives->items as $creative) {
    echo $creative->id;
    echo $creative->campaignId;
    echo $creative->type;
    echo $creative->destinationUrl;
}
```

### Outbound Clicks

Track outbound clicks from your affiliate links:

```php
use JakubOrava\EhubClient\Requests\OutboundClicksListRequest;

$request = (new OutboundClicksListRequest())
    ->from('2024-01-01T00:00:00')
    ->to('2024-01-31T23:59:59')
    ->campaignId('campaign-id')
    ->processed(true)
    ->sort('-dateTime');

$clicks = $client->publishers()
    ->outboundClicks()
    ->list($publisherId, $request);

foreach ($clicks->items as $click) {
    echo $click->id;
    echo $click->campaignId;
    echo $click->clickDateTime;
    echo $click->commission;
}
```

### Transactions

List and retrieve transaction details:

```php
use JakubOrava\EhubClient\Requests\TransactionsListRequest;

// List transactions
$request = (new TransactionsListRequest())
    ->dateInsertedFrom('2024-01-01T00:00:00')
    ->dateInsertedTo('2024-01-31T23:59:59')
    ->campaignId('campaign-id')
    ->status('approved')
    ->payoutStatus('paid')
    ->sort('-dateInserted');

$transactions = $client->publishers()
    ->transactions()
    ->list($publisherId, $request);

foreach ($transactions->items as $transaction) {
    echo $transaction->id;
    echo $transaction->uuid;
    echo $transaction->commission;
    echo $transaction->amount;
    echo $transaction->status;
}

// Get single transaction detail
$transaction = $client->publishers()
    ->transactions()
    ->get($publisherId, 'transaction-id');

echo $transaction->orderId;
echo $transaction->productId;
```

### Vouchers

List vouchers and promotional codes:

```php
use JakubOrava\EhubClient\Requests\VouchersListRequest;

$request = (new VouchersListRequest())
    ->type('voucher') // or 'action'
    ->isValid(true)
    ->sort('-validFrom');

$vouchers = $client->publishers()
    ->vouchers()
    ->list($publisherId, $request);

foreach ($vouchers->items as $voucher) {
    echo $voucher->code;
    echo $voucher->campaignName;
    echo $voucher->rules;
    echo $voucher->url; // Affiliate tracking URL
    echo $voucher->destinationUrl;
    echo $voucher->validFrom;
    echo $voucher->validTill;
}
```

## Error Handling

The package throws specific exceptions for different error scenarios:

```php
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\ValidationException;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;

try {
    $vouchers = $client->publishers()
        ->vouchers()
        ->list($publisherId);
} catch (AuthenticationException $e) {
    // Invalid API key
} catch (ValidationException $e) {
    // Invalid request parameters
} catch (ApiErrorException $e) {
    // API returned an error (4xx or 5xx)
    echo $e->getCode(); // HTTP status code
} catch (UnexpectedResponseException $e) {
    // Unexpected response format
}
```

## Architecture

### DTOs (Data Transfer Objects)

All responses are mapped to strictly typed DTOs with `public readonly` properties:

```php
$campaign = $campaigns->items->first();

// All properties are typed and readonly
$campaign->id;              // string
$campaign->name;            // string
$campaign->cookieLifetime;  // int|null
$campaign->categories;      // Collection<CampaignCategoryDTO>
```

### Request Objects

Request objects use a fluent interface and extend `BaseRequest`:

```php
class VouchersListRequest extends BaseRequest
{
    public function type(string $type): self;
    public function isValid(bool $isValid): self;
}
```

All request objects support:
- `page(int $page)` - Set page number
- `perPage(int $perPage)` - Set items per page
- `sort(string $sort)` - Set sort field (prefix with `-` for descending)
- `fields(array $fields)` - Specify which fields to return

### Future: Advertiser Endpoints

The package architecture is designed to support Advertiser endpoints in the future without breaking changes. The folder structure is prepared:

```
src/Endpoints/
├── PublisherEndpoints/
└── AdvertiserEndpoints/ (future)
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jakub Orava](https://github.com/jakuborava)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
