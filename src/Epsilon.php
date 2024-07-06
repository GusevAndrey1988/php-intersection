<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

class Epsilon
{
    public function __construct(private float $value)
    {
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equal(float $v1, float $v2): bool
    {
        return abs($v1 - $v2) <= $this->value;
    }
}
