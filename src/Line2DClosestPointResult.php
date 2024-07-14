<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

final class Line2DClosestPointResult
{
    public const int EDGE = 0;
    public const int START_VERTEX = 1;
    public const int END_VERTEX = 2;

    public function __construct(
        public readonly Vector2D $point,
        public readonly int $position
    ) {
    }
}
