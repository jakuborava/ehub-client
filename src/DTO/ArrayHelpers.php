<?php

namespace JakubOrava\EhubClient\DTO;

trait ArrayHelpers
{
    /**
     * @param array<string, mixed> $data
     */
    private static function getString(array $data, string $key): string
    {
        $value = $data[$key];

        if (is_string($value)) {
            return $value;
        }

        throw new \InvalidArgumentException("Expected string for key '{$key}', got " . get_debug_type($value));
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function getInt(array $data, string $key): int
    {
        $value = $data[$key];

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        throw new \InvalidArgumentException("Expected int for key '{$key}', got " . get_debug_type($value));
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function getFloat(array $data, string $key): float
    {
        $value = $data[$key];

        if (is_float($value) || is_int($value)) {
            return (float) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        throw new \InvalidArgumentException("Expected float for key '{$key}', got " . get_debug_type($value));
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function getStringOrNull(array $data, string $key): ?string
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        $value = $data[$key];

        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        throw new \InvalidArgumentException("Expected string or null for key '{$key}', got " . get_debug_type($value));
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function getIntOrNull(array $data, string $key): ?int
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        $value = $data[$key];

        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        throw new \InvalidArgumentException("Expected int or null for key '{$key}', got " . get_debug_type($value));
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function getFloatOrNull(array $data, string $key): ?float
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        $value = $data[$key];

        if ($value === null) {
            return null;
        }

        if (is_float($value) || is_int($value)) {
            return (float) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        throw new \InvalidArgumentException("Expected float or null for key '{$key}', got " . get_debug_type($value));
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function getBoolOrNull(array $data, string $key): ?bool
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        $value = $data[$key];

        if ($value === null) {
            return $value;
        }

        if (is_bool($value)) {
            return $value;
        }

        throw new \InvalidArgumentException("Expected bool or null for key '{$key}', got " . get_debug_type($value));
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private static function getArray(array $data, string $key): array
    {
        $value = $data[$key];

        if (!is_array($value)) {
            throw new \InvalidArgumentException("Expected array for key '{$key}', got " . get_debug_type($value));
        }

        // Ensure all keys are strings or integers (valid array keys)
        /** @var array<string, mixed> */
        return $value;
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function getCarbonOrNull(array $data, string $key): ?\Carbon\Carbon
    {
        $value = self::getStringOrNull($data, $key);

        if ($value === null) {
            return null;
        }

        return \Carbon\Carbon::parse($value);
    }
}
