<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection\Tests;

use Lightsaber\PhpIntersection\Math2D;
use Lightsaber\PhpIntersection\Vector2D;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Math2D::class)]
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
        //TODO: add more test cases
        $this->assertEquals(
            new Vector2D(0.5, 0.0),
            Math2D::closestPointOnLine(
                new Vector2D(0.5, 0.5),
                new Vector2D(0.0, 0.0),
                new Vector2D(1.0, 0.0)
            )
        );
    }
}
