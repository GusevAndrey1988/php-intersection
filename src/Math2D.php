<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

class Math2D
{
    public const DEFAULT_EPSILON = 0.000000000001;

    public static function closestPointOnLine(
        Vector2D $point,
        Vector2D $start,
        Vector2D $end
    ): Line2DClosestPointResult {
        $a = $end->sub($start);
        $b = $point->sub($start);
        $c = $b->dotProduct($a) / $a->squareLength();
        $c = self::clamp($c, 0.0, 1.0);

        $position = Line2DClosestPointResult::EDGE;
        if ($c === 0.0) {
            $position = Line2DClosestPointResult::START_VERTEX;
        }
        if ($c === 1.0) {
            $position = Line2DClosestPointResult::END_VERTEX;
        }

        return new Line2DClosestPointResult(
            $start->sum($a->mul($c)),
            $position
        );
    }

    public static function edgeNormal(
        Vector2D $a,
        Vector2D $b,
        Epsilon $epsilon = new Epsilon(self::DEFAULT_EPSILON)
    ): Vector2D {
        $edge = $b->sub($a);
        return (new Vector2D(-$edge->y(), $edge->x()))
            ->normalize($epsilon);
    }

    public static function clamp(float $value, float $min, float $max): float
    {
        return max($min, min($max, $value));
    }
}
