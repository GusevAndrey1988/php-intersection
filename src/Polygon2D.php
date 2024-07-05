<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

class Polygon2D
{
    /**
     * @param Vector2D[] $points
     */ 
    public function __construct(private array $points)
    {
    }

    public function __toString(): string
    {
        return '[' . implode(',', $this->points) . ']';
    }
}
