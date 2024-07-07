<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection\Tests;

use Lightsaber\PhpIntersection\Epsilon;
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

    public function testFromArray(): void
    {
        $points = [
            [1, 2],
            [3, 4],
            [5, 6]
        ];
        $polygon = Polygon2D::fromArray($points);
        self::assertEquals($points, $polygon->toArray());
    }

    public function testFindClosestPoint(): void
    {
        $polygon = Polygon2D::fromArray([
            [0, 0],
            [1, 3],
            [4, 4],
            [6, 2],
            [5, -1],
        ]);

        $epsilon = new Epsilon(0.0000000001);

        $point1 = $polygon->findClosestPoint(new Vector2D(2, 2));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(0.8, 2.4),
            $point1,
            $epsilon
        ));

        $point2 = $polygon->findClosestPoint(new Vector2D(3, 0));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(2.88461538462, -0.576923076923),
            $point2,
            $epsilon
        ));

        $point3 = $polygon->findClosestPoint(new Vector2D(6, 1));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(5.7, 1.1),
            $point3,
            $epsilon
        ));

        $point4 = $polygon->findClosestPoint(new Vector2D(6, -2));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(5, -1),
            $point4,
            $epsilon
        ));

        $point5 = $polygon->findClosestPoint(new Vector2D(5, 3));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(5, 3),
            $point5,
            $epsilon
        ));
    }

    public function testEdgeNormals(): void
    {
        $polygon = Polygon2D::fromArray([
            [1, 0],
            [-3, 1],
            [-4, 2],
            [-1, 3],
            [0, 1],
        ]);

        $expectedNormals = [
            [-0.24253562503633297, -0.9701425001453319],
            [-0.7071067811865475, -0.7071067811865475],
            [-0.31622776601683794, 0.9486832980505138],
            [0.8944271909999159, 0.4472135954999579],
            [0.7071067811865475, 0.7071067811865475],
        ];

        $this->assertEquals(
            $expectedNormals,
            array_map(
                fn (Vector2D $vector) => [$vector->x(), $vector->y()],
                $polygon->edgeNormals()
            )
        );
    }

    private function vectorEqualsWithEpsilon(
        Vector2D $a,
        Vector2D $b,
        Epsilon $epsilon
    ): bool {
        return $epsilon->equal($a->x(), $b->x())
            && $epsilon->equal($a->y(), $b->y());
    }
}
