<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

class Polygon2D
{
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
