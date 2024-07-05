<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

class Math2D
{
    public static function closestPointOnLine(
        Vector2D $point,
        Vector2D $start,
        Vector2D $end
    ): Vector2D
    {
        $a = $end->sub($start);
        $b = $point->sub($start);
        $c = $b->dotProduct($a) / $a->squareLength();
        $c = self::clamp($c, 0, 1);

        return $start->sum($a->mul($c));
    }

    public static function clamp(float $value, float $min, float $max): float
    {
        return max($min, min($max, $value));
    }
}