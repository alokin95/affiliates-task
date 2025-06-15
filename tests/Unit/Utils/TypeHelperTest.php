<?php

namespace Tests\Unit\Utils;

use App\Utils\TypeHelper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TypeHelperTest extends TestCase
{
    public function testToIntStrict_accepts_int_and_digit_string(): void
    {
        $this->assertSame(5, TypeHelper::toIntStrict(5));
        $this->assertSame(42, TypeHelper::toIntStrict('42', 'per_page'));
    }

    public function testToIntStrict_rejects_invalid_values(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected integer for per_page, got string');
        TypeHelper::toIntStrict('4.2', 'per_page');
    }

    public function testToIntOrDefault_returns_default_on_invalid(): void
    {
        $this->assertSame(10, TypeHelper::toIntOrDefault('abc', 10));
        $this->assertSame(20, TypeHelper::toIntOrDefault(20, 10));
        $this->assertSame(7, TypeHelper::toIntOrDefault('7', 10));
    }

    public function testToFloatStrict_accepts_numeric(): void
    {
        $this->assertSame(3.14, TypeHelper::toFloatStrict('3.14'));
        $this->assertSame(6.0, TypeHelper::toFloatStrict(6));
    }

    public function testToFloatStrict_rejects_non_numeric(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected float for rate, got array');
        TypeHelper::toFloatStrict([], 'rate');
    }

    public function testToFloatOrDefault_returns_default_on_invalid(): void
    {
        $this->assertSame(1.23, TypeHelper::toFloatOrDefault('xyz', 1.23));
        $this->assertSame(7.0, TypeHelper::toFloatOrDefault('7', 2.0));
    }

    public function toStringStrict_accepts_strings(): void
    {
        $this->assertSame('hello', TypeHelper::toStringStrict('hello'));
    }

    public function toStringStrict_rejects_non_strings(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected string for url, got int');
        TypeHelper::toStringStrict(123, 'url');
    }

    public function toStringOrDefault_returns_cast_or_default(): void
    {
        $this->assertSame('true', TypeHelper::toStringOrDefault(true, 'x'));
        $this->assertSame('xyz', TypeHelper::toStringOrDefault('xyz', 'x'));
        $this->assertSame('x', TypeHelper::toStringOrDefault([], 'x'));
    }
}
