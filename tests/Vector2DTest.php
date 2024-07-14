<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection\Tests;

use Lightsaber\PhpIntersection\Epsilon;
use Lightsaber\PhpIntersection\Vector2D;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Vector2D::class)]
#[UsesClass(Epsilon::class)]
class Vector2DTest extends TestCase
{
    public function testToString(): void
    {
        $vector = new Vector2D(1.0, 2.0);
        self::assertEquals('(1, 2)', (string)$vector);
    }
    public function testSum(): void
    {
        $vec1 = new Vector2D(1.0, 2.0);
        $vec2 = new Vector2D(3.0, 4.0);
        self::assertEquals(new Vector2D(4.0, 6.0), $vec1->sum($vec2));
    }

    public function testSub(): void
    {
        $vec1 = new Vector2D(1.0, 2.0);
        $vec2 = new Vector2D(3.0, 4.0);
        self::assertEquals(new Vector2D(-2.0, -2.0), $vec1->sub($vec2));
    }

    public function testMul(): void
    {
        $vec1 = new Vector2D(1.0, 2.0);
        self::assertEquals(new Vector2D(3.0, 6.0), $vec1->mul(3.0));

        $vec1 = new Vector2D(0.0, 0.0);
        self::assertEquals(new Vector2D(0.0, 0.0), $vec1->mul(0.0));

        $vec1 = new Vector2D(3.0, 4.0);
        self::assertEquals(new Vector2D(0.0, 0.0), $vec1->mul(0.0));
    }

    public function testDiv(): void
    {
        $vec1 = new Vector2D(1.0, 2.0);
        self::assertEquals(new Vector2D(0.5, 1.0), $vec1->div(2.0));
    }

    public function testDivByZero(): void
    {
        $vec1 = new Vector2D(1.0, 2.0);
        $this->expectException(\DivisionByZeroError::class);
        $vec1->div(0.0);
    }

    public function testSquareLength(): void
    {
        $vector = new Vector2D(3.0, 4.0);
        self::assertEquals(25, $vector->squareLength());

        $vector = new Vector2D(0.0, 0.0);
        self::assertEquals(0, $vector->squareLength());
    }

    public function testLength(): void
    {
        $vector = new Vector2D(3.0, 4.0);
        self::assertEquals(5, $vector->length());

        $vector = new Vector2D(0.0, 0.0);
        self::assertEquals(0, $vector->length());
    }

    public function testDotProduct(): void
    {
        $vec1 = new Vector2D(1.0, 2.0);
        $vec2 = new Vector2D(3.0, 4.0);
        self::assertEquals(11, $vec1->dotProduct($vec2));

        $vec1 = new Vector2D(0.0, 0.0);
        $vec2 = new Vector2D(0.0, 0.0);
        self::assertEquals(0, $vec1->dotProduct($vec2));

        $vec1 = new Vector2D(3.0, 4.0);
        $vec2 = new Vector2D(0.0, 0.0);
        self::assertEquals(0, $vec1->dotProduct($vec2));
    }

    public function testDistance(): void
    {
        $vec1 = new Vector2D(0.0, 0.0);
        $vec2 = new Vector2D(3.0, 4.0);
        self::assertEquals(5, $vec1->distance($vec2));

        $vec1 = new Vector2D(0.0, 0.0);
        $vec2 = new Vector2D(0.0, 0.0);
        self::assertEquals(0, $vec1->distance($vec2));

        $vec1 = new Vector2D(1.0, 4.0);
        $vec2 = new Vector2D(2.0, 3.0);
        self::assertEquals(sqrt(2.0), $vec1->distance($vec2));
    }

    public function testNormalize(): void
    {
        $epsilon = new Epsilon(0.0000000001);

        $vec1 = new Vector2D(3.0, 4.0);
        $vec1 = $vec1->normalize();
        self::assertTrue($epsilon->equal(1.0, $vec1->length()));
    }

    public function testNormalizeZeroLength(): void
    {
        $this->expectException(\Exception::class);
        $vec1 = new Vector2D(0.0, 0.0);
        $vec1->normalize();
    }
}
