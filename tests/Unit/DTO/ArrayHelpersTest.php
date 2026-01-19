<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;
use JakubOrava\EhubClient\DTO\ArrayHelpers;

// Test class that uses ArrayHelpers trait
class TestArrayHelperClass
{
    use ArrayHelpers;

    public static function testGetString(array $data, string $key): string
    {
        return self::getString($data, $key);
    }

    public static function testGetInt(array $data, string $key): int
    {
        return self::getInt($data, $key);
    }

    public static function testGetFloat(array $data, string $key): float
    {
        return self::getFloat($data, $key);
    }

    public static function testGetStringOrNull(array $data, string $key): ?string
    {
        return self::getStringOrNull($data, $key);
    }

    public static function testGetIntOrNull(array $data, string $key): ?int
    {
        return self::getIntOrNull($data, $key);
    }

    public static function testGetFloatOrNull(array $data, string $key): ?float
    {
        return self::getFloatOrNull($data, $key);
    }

    public static function testGetBoolOrNull(array $data, string $key): ?bool
    {
        return self::getBoolOrNull($data, $key);
    }

    public static function testGetArray(array $data, string $key): array
    {
        return self::getArray($data, $key);
    }

    public static function testGetCarbonOrNull(array $data, string $key): ?Carbon
    {
        return self::getCarbonOrNull($data, $key);
    }

    public static function testGetCollection(array $data, string $key, callable $mapper): Collection
    {
        return self::getCollection($data, $key, $mapper);
    }
}

describe('getString', function () {
    it('returns string value', function () {
        $data = ['name' => 'Test Name'];
        $result = TestArrayHelperClass::testGetString($data, 'name');

        expect($result)->toBe('Test Name');
    });

    it('throws exception for non-string value', function () {
        $data = ['name' => 123];
        TestArrayHelperClass::testGetString($data, 'name');
    })->throws(InvalidArgumentException::class, 'Expected string for key');

    it('throws exception for array value', function () {
        $data = ['name' => ['foo', 'bar']];
        TestArrayHelperClass::testGetString($data, 'name');
    })->throws(InvalidArgumentException::class);

    it('throws exception for null value', function () {
        $data = ['name' => null];
        TestArrayHelperClass::testGetString($data, 'name');
    })->throws(InvalidArgumentException::class);
});

describe('getInt', function () {
    it('returns int value', function () {
        $data = ['count' => 42];
        $result = TestArrayHelperClass::testGetInt($data, 'count');

        expect($result)->toBe(42);
    });

    it('converts numeric string to int', function () {
        $data = ['count' => '42'];
        $result = TestArrayHelperClass::testGetInt($data, 'count');

        expect($result)->toBe(42);
    });

    it('converts float string to int', function () {
        $data = ['count' => '42.9'];
        $result = TestArrayHelperClass::testGetInt($data, 'count');

        expect($result)->toBe(42);
    });

    it('throws exception for non-numeric value', function () {
        $data = ['count' => 'not a number'];
        TestArrayHelperClass::testGetInt($data, 'count');
    })->throws(InvalidArgumentException::class, 'Expected int for key');

    it('throws exception for array value', function () {
        $data = ['count' => [1, 2, 3]];
        TestArrayHelperClass::testGetInt($data, 'count');
    })->throws(InvalidArgumentException::class);
});

describe('getFloat', function () {
    it('returns float value', function () {
        $data = ['price' => 19.99];
        $result = TestArrayHelperClass::testGetFloat($data, 'price');

        expect($result)->toBe(19.99);
    });

    it('converts int to float', function () {
        $data = ['price' => 20];
        $result = TestArrayHelperClass::testGetFloat($data, 'price');

        expect($result)->toBe(20.0);
    });

    it('converts numeric string to float', function () {
        $data = ['price' => '19.99'];
        $result = TestArrayHelperClass::testGetFloat($data, 'price');

        expect($result)->toBe(19.99);
    });

    it('throws exception for non-numeric value', function () {
        $data = ['price' => 'not a number'];
        TestArrayHelperClass::testGetFloat($data, 'price');
    })->throws(InvalidArgumentException::class, 'Expected float for key');
});

describe('getStringOrNull', function () {
    it('returns string value', function () {
        $data = ['name' => 'Test'];
        $result = TestArrayHelperClass::testGetStringOrNull($data, 'name');

        expect($result)->toBe('Test');
    });

    it('returns null when key does not exist', function () {
        $data = ['other' => 'value'];
        $result = TestArrayHelperClass::testGetStringOrNull($data, 'name');

        expect($result)->toBeNull();
    });

    it('returns null when value is null', function () {
        $data = ['name' => null];
        $result = TestArrayHelperClass::testGetStringOrNull($data, 'name');

        expect($result)->toBeNull();
    });

    it('throws exception for non-string value', function () {
        $data = ['name' => 123];
        TestArrayHelperClass::testGetStringOrNull($data, 'name');
    })->throws(InvalidArgumentException::class);
});

describe('getIntOrNull', function () {
    it('returns int value', function () {
        $data = ['count' => 42];
        $result = TestArrayHelperClass::testGetIntOrNull($data, 'count');

        expect($result)->toBe(42);
    });

    it('returns null when key does not exist', function () {
        $data = ['other' => 'value'];
        $result = TestArrayHelperClass::testGetIntOrNull($data, 'count');

        expect($result)->toBeNull();
    });

    it('returns null when value is null', function () {
        $data = ['count' => null];
        $result = TestArrayHelperClass::testGetIntOrNull($data, 'count');

        expect($result)->toBeNull();
    });

    it('converts numeric string to int', function () {
        $data = ['count' => '42'];
        $result = TestArrayHelperClass::testGetIntOrNull($data, 'count');

        expect($result)->toBe(42);
    });

    it('throws exception for non-numeric value', function () {
        $data = ['count' => 'not a number'];
        TestArrayHelperClass::testGetIntOrNull($data, 'count');
    })->throws(InvalidArgumentException::class);
});

describe('getFloatOrNull', function () {
    it('returns float value', function () {
        $data = ['price' => 19.99];
        $result = TestArrayHelperClass::testGetFloatOrNull($data, 'price');

        expect($result)->toBe(19.99);
    });

    it('returns null when key does not exist', function () {
        $data = ['other' => 'value'];
        $result = TestArrayHelperClass::testGetFloatOrNull($data, 'price');

        expect($result)->toBeNull();
    });

    it('returns null when value is null', function () {
        $data = ['price' => null];
        $result = TestArrayHelperClass::testGetFloatOrNull($data, 'price');

        expect($result)->toBeNull();
    });

    it('converts int to float', function () {
        $data = ['price' => 20];
        $result = TestArrayHelperClass::testGetFloatOrNull($data, 'price');

        expect($result)->toBe(20.0);
    });
});

describe('getBoolOrNull', function () {
    it('returns bool value true', function () {
        $data = ['active' => true];
        $result = TestArrayHelperClass::testGetBoolOrNull($data, 'active');

        expect($result)->toBeTrue();
    });

    it('returns bool value false', function () {
        $data = ['active' => false];
        $result = TestArrayHelperClass::testGetBoolOrNull($data, 'active');

        expect($result)->toBeFalse();
    });

    it('returns null when key does not exist', function () {
        $data = ['other' => 'value'];
        $result = TestArrayHelperClass::testGetBoolOrNull($data, 'active');

        expect($result)->toBeNull();
    });

    it('returns null when value is null', function () {
        $data = ['active' => null];
        $result = TestArrayHelperClass::testGetBoolOrNull($data, 'active');

        expect($result)->toBeNull();
    });

    it('throws exception for non-bool value', function () {
        $data = ['active' => 'yes'];
        TestArrayHelperClass::testGetBoolOrNull($data, 'active');
    })->throws(InvalidArgumentException::class, 'Expected bool or null for key');

    it('throws exception for int value', function () {
        $data = ['active' => 1];
        TestArrayHelperClass::testGetBoolOrNull($data, 'active');
    })->throws(InvalidArgumentException::class);
});

describe('getArray', function () {
    it('returns array value', function () {
        $data = ['items' => ['a', 'b', 'c']];
        $result = TestArrayHelperClass::testGetArray($data, 'items');

        expect($result)->toBe(['a', 'b', 'c']);
    });

    it('returns empty array', function () {
        $data = ['items' => []];
        $result = TestArrayHelperClass::testGetArray($data, 'items');

        expect($result)->toBe([]);
    });

    it('returns associative array', function () {
        $data = ['config' => ['key' => 'value', 'another' => 'test']];
        $result = TestArrayHelperClass::testGetArray($data, 'config');

        expect($result)->toBe(['key' => 'value', 'another' => 'test']);
    });

    it('throws exception for non-array value', function () {
        $data = ['items' => 'not an array'];
        TestArrayHelperClass::testGetArray($data, 'items');
    })->throws(InvalidArgumentException::class, 'Expected array for key');

    it('throws exception for null value', function () {
        $data = ['items' => null];
        TestArrayHelperClass::testGetArray($data, 'items');
    })->throws(InvalidArgumentException::class);
});

describe('getCarbonOrNull', function () {
    it('returns Carbon instance from valid date string', function () {
        $data = ['date' => '2024-01-15T10:30:00'];
        $result = TestArrayHelperClass::testGetCarbonOrNull($data, 'date');

        expect($result)->toBeInstanceOf(Carbon::class)
            ->and($result->year)->toBe(2024)
            ->and($result->month)->toBe(1)
            ->and($result->day)->toBe(15);
    });

    it('returns null when key does not exist', function () {
        $data = ['other' => 'value'];
        $result = TestArrayHelperClass::testGetCarbonOrNull($data, 'date');

        expect($result)->toBeNull();
    });

    it('returns null when value is null', function () {
        $data = ['date' => null];
        $result = TestArrayHelperClass::testGetCarbonOrNull($data, 'date');

        expect($result)->toBeNull();
    });

    it('parses different date formats', function () {
        $data = ['date' => '2024/01/15'];
        $result = TestArrayHelperClass::testGetCarbonOrNull($data, 'date');

        expect($result)->toBeInstanceOf(Carbon::class)
            ->and($result->year)->toBe(2024);
    });
});

describe('getCollection', function () {
    it('returns Collection with mapped items', function () {
        $data = ['users' => [
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
        ]];

        $result = TestArrayHelperClass::testGetCollection(
            $data,
            'users',
            fn (array $user) => $user['name']
        );

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result->count())->toBe(2)
            ->and($result->first())->toBe('Alice')
            ->and($result->last())->toBe('Bob');
    });

    it('returns empty Collection for empty array', function () {
        $data = ['users' => []];

        $result = TestArrayHelperClass::testGetCollection(
            $data,
            'users',
            fn (array $user) => $user
        );

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result->count())->toBe(0);
    });

    it('throws exception when collection item is not an array', function () {
        $data = ['users' => ['not', 'arrays']];

        TestArrayHelperClass::testGetCollection(
            $data,
            'users',
            fn (array $user) => $user
        );
    })->throws(InvalidArgumentException::class, 'Expected array for collection item');

    it('applies mapper function to each item', function () {
        $data = ['numbers' => [
            ['value' => 5],
            ['value' => 10],
            ['value' => 15],
        ]];

        $result = TestArrayHelperClass::testGetCollection(
            $data,
            'numbers',
            fn (array $item) => $item['value'] * 2
        );

        expect($result->toArray())->toBe([10, 20, 30]);
    });

    it('handles complex object mapping', function () {
        $data = ['items' => [
            ['id' => 1, 'data' => ['nested' => 'value1']],
            ['id' => 2, 'data' => ['nested' => 'value2']],
        ]];

        $result = TestArrayHelperClass::testGetCollection(
            $data,
            'items',
            fn (array $item) => [
                'id' => $item['id'],
                'nested' => $item['data']['nested'],
            ]
        );

        expect($result->first())->toBe(['id' => 1, 'nested' => 'value1'])
            ->and($result->last())->toBe(['id' => 2, 'nested' => 'value2']);
    });
});
