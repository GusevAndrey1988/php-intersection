<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection\Tests;

use Lightsaber\PhpIntersection\Epsilon;
use Lightsaber\PhpIntersection\Line2DClosestPointResult;
use Lightsaber\PhpIntersection\Math2D;
use Lightsaber\PhpIntersection\Vector2D;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Math2D::class)]
#[UsesClass(Epsilon::class)]
#[UsesClass(Vector2D::class)]
#[UsesClass(Line2DClosestPointResult::class)]
class Math2DTest extends TestCase
{
    public function testClamp(): void
    {
        $this->assertSame(0.0, Math2D::clamp(0.0, 0.0, 1.0));
        $this->assertSame(0.5, Math2D::clamp(0.5, 0.0, 1.0));
        $this->assertSame(1.0, Math2D::clamp(1.0, 0.0, 1.0));
    }

    public function testClosestPointOnLine(): void
    {
        $this->assertEquals(
            new Line2DClosestPointResult(
                new Vector2D(0.5, 0.0),
                Line2DClosestPointResult::EDGE
            ),
            Math2D::closestPointOnLine(
                new Vector2D(0.5, 0.5),
                new Vector2D(0.0, 0.0),
                new Vector2D(1.0, 0.0)
            )
        );

        $this->assertEquals(
            new Line2DClosestPointResult(
                new Vector2D(0.5, 0.5),
                Line2DClosestPointResult::EDGE
            ),
            Math2D::closestPointOnLine(
                new Vector2D(0.5, 0.5),
                new Vector2D(0.0, 0.0),
                new Vector2D(1.0, 1.0),
            )
        );

        $this->assertEquals(
            new Line2DClosestPointResult(
                new Vector2D(0.125, 0.875),
                Line2DClosestPointResult::EDGE
            ),
            Math2D::closestPointOnLine(
                new Vector2D(0.25, 1.0),
                new Vector2D(0.0, 1.0),
                new Vector2D(1.0, 0.0),
            )
        );

        $this->assertEquals(
            new Line2DClosestPointResult(
                new Vector2D(1.0, 0.0),
                Line2DClosestPointResult::END_VERTEX
            ),
            Math2D::closestPointOnLine(
                new Vector2D(2.0, -1.0),
                new Vector2D(0.0, 1.0),
                new Vector2D(1.0, 0.0),
            )
        );

        $this->assertEquals(
            new Line2DClosestPointResult(
                new Vector2D(0.0, 1.0),
                Line2DClosestPointResult::START_VERTEX
            ),
            Math2D::closestPointOnLine(
                new Vector2D(-1.0, 2.0),
                new Vector2D(0.0, 1.0),
                new Vector2D(1.0, 0.0),
            )
        );
    }

    public function testEdgeNormal(): void
    {
        $this->assertEquals(
            new Vector2D(1 / M_SQRT2, 1 / M_SQRT2),
            Math2D::edgeNormal(
                new Vector2D(0.0, 1.0),
                new Vector2D(1.0, 0.0),
            )
        );

        $this->assertEquals(
            new Vector2D(-1 / M_SQRT2, -1 / M_SQRT2),
            Math2D::edgeNormal(
                new Vector2D(1.0, 0.0),
                new Vector2D(0.0, 1.0),
            )
        );

        $epsilon = new Epsilon(0.0000000001);
        $normal = Math2D::edgeNormal(
            new Vector2D(1.0, -2.0),
            new Vector2D(-3.0, -1.0),
        );
        $this->assertTrue($epsilon->equal(-0.242535625036, $normal->x()));
        $this->assertTrue($epsilon->equal(-0.970142500145, $normal->y()));
    }

    public function testEdgeNormalWhenZeroLength(): void
    {
        $this->expectException(\Exception::class);
        Math2D::edgeNormal(
            new Vector2D(0.0, 0.0),
            new Vector2D(0.0, 0.0),
        );
    }
}
