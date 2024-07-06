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

    public function findClosestPoint(Vector2D $point): Vector2D
    {
        $closest = $this->points[0];

        $findeClosest = fn (Vector2D $point, Vector2D $a, Vector2D $b)
            => $point->distance($a) < $point->distance($b) ? $a : $b;

        for ($index = 1; $index < count($this->points); $index++) {
            $a = $this->points[$index - 1];
            $b = $this->points[$index];
            $closestPointOnLine = Math2D::closestPointOnLine($point, $a, $b);
            $closest = $findeClosest($point, $closestPointOnLine, $closest);
        }

        $a = $this->points[count($this->points) - 1];
        $b = $this->points[0];
        $closestPointOnLine = Math2D::closestPointOnLine($point, $a, $b);
        $closest = $findeClosest($point, $closestPointOnLine, $closest);

        return $closest;
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
