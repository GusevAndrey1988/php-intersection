<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

class Polygon2D
{
    /**
     * @param array<array{0: float, 1: float}> $points
     */
    public static function fromArray(array $points): Polygon2D
    {
        return new Polygon2D(array_map(
            fn (array $point) => new Vector2D($point[0], $point[1]),
            $points
        ));
    }

    /**
     * @param Vector2D[] $points
     */
    public function __construct(private array $points)
    {
    }

    /**
     * @return Vector2D[]
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    public function findClosestPoint(
        Vector2D $point
    ): Polygon2DClosestPointResult {
        $closest = new Line2DClosestPointResult(
            $this->points[0],
            Line2DClosestPointResult::START_VERTEX
        );

        $findClosest = function (
            Vector2D $point,
            Line2DClosestPointResult &$closest,
            Line2DClosestPointResult $b,
        ): bool {
            if ($point->distance($b->point) < $point->distance($closest->point)) {
                $closest = $b;
                return true;
            }
            return false;
        };

        $edgeId = null;
        $vertexId = 0;
        for ($index = 1; $index < count($this->points); $index++) {
            $a = $this->points[$index - 1];
            $b = $this->points[$index];
            $closestPointOnLineResult
                = Math2D::closestPointOnLine($point, $a, $b);
            if ($findClosest($point, $closest, $closestPointOnLineResult)) {
                if ($closest->position === Line2DClosestPointResult::EDGE) {
                    $edgeId = $index - 1;
                    $vertexId = null;
                } else {
                    $edgeId = null;

                    if ($closest->position === Line2DClosestPointResult::START_VERTEX) {
                        $vertexId = $index - 1;
                    } else {
                        $vertexId = $index;
                    }
                }
            }
        }

        $a = $this->points[count($this->points) - 1];
        $b = $this->points[0];
        $closestPointOnLine = Math2D::closestPointOnLine($point, $a, $b);
        if ($findClosest($point, $closest, $closestPointOnLine)) {
            if ($closest->position === Line2DClosestPointResult::EDGE) {
                $edgeId = count($this->points) - 1;
                $vertexId = null;
            } else {
                $edgeId = null;

                if ($closest->position === Line2DClosestPointResult::START_VERTEX) {
                    $vertexId = count($this->points) - 1;
                } else {
                    $vertexId = 0;
                }
            }
        }

        return new Polygon2DClosestPointResult(
            $closest->point,
            $edgeId,
            $vertexId
        );
    }

    /**
     * @return Vector2D[]
     */
    public function edgeNormals(
        Epsilon $epsilon = new Epsilon(Math2D::DEFAULT_EPSILON)
    ): array {
        /** @var Vector2D[] */
        $normals = [];

        for ($intex = 1; $intex < count($this->points); $intex++) {
            $v = $this->points[$intex - 1];
            $w = $this->points[$intex];

            $normals[] = Math2D::edgeNormal($v, $w, $epsilon);
        }

        $normals[] = Math2D::edgeNormal(
            $this->points[count($this->points) - 1],
            $this->points[0],
            $epsilon
        );

        return $normals;
    }

    /**
     * @param Vector2D[] $edgesNormals
     * @return Vector2D[]
     */
    public static function verticesNormals(array $edgesNormals): array
    {
        $verticesNormals = [];
        for ($idx = 1; $idx < count($edgesNormals); $idx++) {
            $edge1Normal = $edgesNormals[$idx - 1];
            $edge2Normal = $edgesNormals[$idx];
            $verticesNormals[$idx]
                = $edge1Normal->sum($edge2Normal)->normalize();
        }

        $verticesNormals[0]
            = $edgesNormals[count($edgesNormals) - 1]
                ->sum($edgesNormals[0])
                ->normalize();

        return $verticesNormals;
    }

    /**
     * @param Vector2D[] $edgesNormals
     * @param Vector2D[] $verticesNormals
     */
    public function includePoint(
        Vector2D $point,
        array $edgesNormals,
        array $verticesNormals
    ): bool {
        $closestPoint = $this->findClosestPoint($point);
        $closestPointNormal = !is_null($closestPoint->edgeId)
            ? $edgesNormals[$closestPoint->edgeId]
            : $verticesNormals[$closestPoint->vertexId];
        $vector = $closestPoint->point->sub($point);
        return $closestPointNormal->dotProduct($vector) > 0;
    }

    /**
     * @return array<array{0: float, 1: float}>
     */
    public function toArray(): array
    {
        return array_map(
            fn (Vector2D $point) => [$point->x(), $point->y()],
            $this->points
        );
    }

    public function __toString(): string
    {
        return '[' . implode(',', $this->points) . ']';
    }
}
