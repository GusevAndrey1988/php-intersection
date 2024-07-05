<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection\Tests;

use Lightsaber\PhpIntersection\Vector2D;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Vector2D::class)]
class Vector2DTest extends TestCase
{
    public function testToString(): void
    {
        $vector = new Vector2D(1, 2);
        self::assertEquals('(1, 2)', (string)$vector);
    }

    public function testSum(): void
    {
        $vec1 = new Vector2D(1, 2);
        $vec2 = new Vector2D(3, 4);
        self::assertEquals(new Vector2D(4, 6), $vec1->sum($vec2));
    }

    public function testSub(): void
    {
        $vec1 = new Vector2D(1, 2);
        $vec2 = new Vector2D(3, 4);
        self::assertEquals(new Vector2D(-2, -2), $vec1->sub($vec2));
    }
}
