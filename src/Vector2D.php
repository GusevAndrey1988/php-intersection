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

    public function mul(float $number): Vector2D
    {
        return new Vector2D($this->x * $number, $this->y * $number);
    }

    public function div(float $number): Vector2D
    {
        return new Vector2D($this->x / $number, $this->y / $number);
    }

    public function squareLength(): float
    {
        return pow($this->x, 2) + pow($this->y, 2);
    }

    public function length(): float
    {
        return sqrt($this->squareLength());
    }

    public function dotProduct(Vector2D $vector): float
    {
        return $this->x * $vector->x() + $this->y * $vector->y();
    }

    public function distance(Vector2D $vector): float
    {
        return $this->sub($vector)->length();
    }

    /**
     * @throws \Exception if length is 0
     */
    public function normalize(
        Epsilon $epsilon = new Epsilon(Math2D::DEFAULT_EPSILON)
    ): Vector2D {
        $length = $this->length();
        if ($epsilon->equal(0, $length)) {
            throw new \Exception('Vector length is 0');
        }
        return $this->div($length);
    }

    public function __toString(): string
    {
        return sprintf('(%s, %s)', $this->x, $this->y);
    }
}
