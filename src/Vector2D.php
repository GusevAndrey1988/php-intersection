<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

class Vector2D
{
    public function __construct(private float $x, private float $y)
    {
    }

    public function x(): float
    {
        return $this->x;
    }

    public function y(): float
    {
        return $this->y;
    }

    public function sum(Vector2D $vector): Vector2D
    {
        return new Vector2D($this->x + $vector->x(), $this->y + $vector->y());
    }

    public function sub(Vector2D $vector): Vector2D
    {
        return new Vector2D($this->x - $vector->x(), $this->y - $vector->y());
    }

    public function __toString(): string
    {
        return sprintf('(%s, %s)', $this->x, $this->y);
    }
}
