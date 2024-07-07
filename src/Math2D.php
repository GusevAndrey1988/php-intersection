<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

class Math2D
{
    public const DEFAULT_EPSILON = 0.0000000001;

    public static function closestPointOnLine(
        Vector2D $point,
        Vector2D $start,
        Vector2D $end
    ): Vector2D {
        $a = $end->sub($start);
        $b = $point->sub($start);
        $c = $b->dotProduct($a) / $a->squareLength();
        $c = self::clamp($c, 0, 1);

        return $start->sum($a->mul($c));
    }

    public static function edgeNormal(Vector2D $a, Vector2D $b): Vector2D
    {
        $edge = $b->sub($a);
        return (new Vector2D(-$edge->y(), $edge->x()))->normalize();
    }

    public static function clamp(float $value, float $min, float $max): float
    {
        return max($min, min($max, $value));
    }
}
