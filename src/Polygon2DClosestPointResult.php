<?php

declare(strict_types=1);

namespace Lightsaber\PhpIntersection;

final class Polygon2DClosestPointResult
{
    public function __construct(
        public readonly Vector2D $point,
        public readonly ?int $edgeId,
        public readonly ?int $vertexId
    ) {
    }
}
