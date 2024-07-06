<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection\Tests;

use Lightsaber\PhpIntersection\Epsilon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Epsilon::class)]
class EpsilonTest extends TestCase
{
    public function testEqual(): void
    {
        $epsilon1 = new Epsilon(0.00000001);
        self::assertTrue($epsilon1->equal(0.0, 0.0));
        self::assertFalse($epsilon1->equal(0.0, 1.0));
        self::assertTrue($epsilon1->equal(
            1.000000002,
            1.000000001
        ));
        self::assertFalse($epsilon1->equal(
            1.00000002,
            1.00000001
        ));
    }

    public function testEqualWhenEpsilonIsZero(): void
    {
        $epsilon1 = new Epsilon(0.0);
        self::assertTrue($epsilon1->equal(0.0, 0.0));
        self::assertFalse($epsilon1->equal(0.0, 1.0));
        self::assertFalse($epsilon1->equal(
            1.000000002,
            1.000000001
        ));
    }
}
