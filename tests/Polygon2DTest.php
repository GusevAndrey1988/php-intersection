<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection\Tests;

use Lightsaber\PhpIntersection\Polygon2D;
use Lightsaber\PhpIntersection\Vector2D;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Polygon2D::class)]
class Polygon2DTest extends TestCase
{
    public function testToString(): void
    {
        $polygon = new Polygon2D([
            new Vector2D(1, 2),
            new Vector2D(3, 4),
            new Vector2D(5, 6)
        ]);
        self::assertEquals('[(1, 2),(3, 4),(5, 6)]', (string)$polygon);
    }
}
