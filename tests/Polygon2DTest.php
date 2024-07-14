<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection\Tests;

use Lightsaber\PhpIntersection\Epsilon;
use Lightsaber\PhpIntersection\Line2DClosestPointResult;
use Lightsaber\PhpIntersection\Math2D;
use Lightsaber\PhpIntersection\Polygon2D;
use Lightsaber\PhpIntersection\Polygon2DClosestPointResult;
use Lightsaber\PhpIntersection\Vector2D;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Polygon2D::class)]
#[UsesClass(Epsilon::class)]
#[UsesClass(Vector2D::class)]
#[UsesClass(Math2D::class)]
#[UsesClass(Line2DClosestPointResult::class)]
#[UsesClass(Polygon2DClosestPointResult::class)]
class Polygon2DTest extends TestCase
{
    public function testToString(): void
    {
        $polygon = new Polygon2D([
            new Vector2D(1.0, 2.0),
            new Vector2D(3.0, 4.0),
            new Vector2D(5.0, 6.0)
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

        $point1 = $polygon->findClosestPoint(new Vector2D(2.0, 2.0));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(0.8, 2.4),
            $point1->point,
            $epsilon
        ));
        self::assertEquals(0, $point1->edgeId);
        self::assertNull($point1->vertexId);

        $point2 = $polygon->findClosestPoint(new Vector2D(3.0, 0.0));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(2.88461538462, -0.576923076923),
            $point2->point,
            $epsilon
        ));
        self::assertEquals(4, $point2->edgeId);
        self::assertNull($point2->vertexId);

        $point3 = $polygon->findClosestPoint(new Vector2D(6.0, 1.0));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(5.7, 1.1),
            $point3->point,
            $epsilon
        ));
        self::assertEquals(3, $point3->edgeId);
        self::assertNull($point3->vertexId);

        $point4 = $polygon->findClosestPoint(new Vector2D(6.0, -2.0));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(5.0, -1.0),
            $point4->point,
            $epsilon
        ));
        self::assertNull($point4->edgeId);
        self::assertEquals(4, $point4->vertexId);

        $point5 = $polygon->findClosestPoint(new Vector2D(5.0, 3.0));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(5.0, 3.0),
            $point5->point,
            $epsilon
        ));
        self::assertEquals(2, $point5->edgeId);
        self::assertNull($point5->vertexId);

        $point6 = $polygon->findClosestPoint(new Vector2D(-1.0, -1.0));
        self::assertTrue($this->vectorEqualsWithEpsilon(
            new Vector2D(0.0, 0.0),
            $point6->point,
            $epsilon
        ));
        self::assertNull($point6->edgeId);
        self::assertEquals(0, $point6->vertexId);
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

    public function testVertexNormals(): void
    {
        $edgesNormals = [
            new Vector2D(0.0, 1.0),
            new Vector2D(1.0, 0.0),
            new Vector2D(-0.7071067811865475, -0.7071067811865475),
        ];

        $this->assertEquals(
            [
                new Vector2D(-0.9238795325112867, 0.3826834323650899),
                new Vector2D(0.7071067811865475, 0.7071067811865475),
                new Vector2D(0.3826834323650899, -0.9238795325112867),
            ],
            Polygon2D::verticesNormals($edgesNormals)
        );
    }

    public function testIncludePoint(): void
    {
        $polygon = Polygon2D::fromArray([
            [0, 0],
            [1, 3],
            [4, 4],
            [6, 2],
            [5, -1],
        ]);

        $edgeNormals = $polygon->edgeNormals();
        $verticesNormals = Polygon2D::verticesNormals($edgeNormals);

        $test = fn (Vector2D $point): bool => $polygon->includePoint(
            $point,
            $edgeNormals,
            $verticesNormals
        );

        $this->assertTrue($test(new Vector2D(2.0, 2.0)));
        $this->assertTrue($test(new Vector2D(4.0, 0.0)));
        $this->assertTrue($test(new Vector2D(1.0, 0.9)));
        $this->assertTrue($test(new Vector2D(5.0, 2.9)));

        $this->assertFalse($test(new Vector2D(-1.0, -1.0)));
        $this->assertFalse($test(new Vector2D(6.0, 1.0)));
        $this->assertFalse($test(new Vector2D(2.0, 4.0)));
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
