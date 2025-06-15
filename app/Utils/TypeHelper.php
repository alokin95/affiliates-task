<?php

namespace App\Utils;

use InvalidArgumentException;

final class TypeHelper
{
    /**
     * Strict: must be integer or numeric string, otherwise throws.
     *
     * @param mixed $value
     * @param string $context
     * @return int
     */
    public static function toIntStrict(mixed $value, string $context = ''): int
    {
        if (is_int($value) || (is_string($value) && ctype_digit($value))) {
            return (int) $value;
        }
        throw new InvalidArgumentException(
            sprintf('Expected integer%s, got %s', $context ? " for $context" : '', get_debug_type($value))
        );
    }

    /**
     * Lenient: if non-numeric, returns default.
     *
     * @param mixed $value
     * @param int $default
     * @return int
     */
    public static function toIntOrDefault(mixed $value, int $default): int
    {
        return (is_int($value) || (is_string($value) && ctype_digit($value)))
            ? (int) $value
            : $default;
    }

    /**
     * Strict: must be float/int or numeric string, otherwise throws.
     *
     * @param mixed $value
     * @param string $context
     * @return float
     */
    public static function toFloatStrict(mixed $value, string $context = ''): float
    {
        if (is_float($value) || is_int($value) || (is_string($value) && is_numeric($value))) {
            return (float) $value;
        }
        throw new InvalidArgumentException(
            sprintf('Expected float%s, got %s', $context ? " for $context" : '', get_debug_type($value))
        );
    }

    /**
     * Lenient: if non-numeric, returns default.
     *
     * @param mixed $value
     * @param float $default
     * @return float
     */
    public static function toFloatOrDefault(mixed $value, float $default): float
    {
        return (is_float($value) || is_int($value) || (is_string($value) && is_numeric($value)))
            ? (float) $value
            : $default;
    }

    /**
     * Strict: must be string, otherwise throws.
     *
     * @param mixed $value
     * @param string $context
     * @return string
     */
    public static function toStringStrict(mixed $value, string $context = ''): string
    {
        if (is_string($value)) {
            return $value;
        }
        throw new InvalidArgumentException(
            sprintf('Expected string%s, got %s', $context ? " for $context" : '', get_debug_type($value))
        );
    }

    /**
     * Lenient: returns string or default-cast.
     *
     * @param mixed $value
     * @param string $default
     * @return string
     */
    public static function toStringOrDefault(mixed $value, string $default): string
    {
        return is_scalar($value) ? (string) $value : $default;
    }
}
