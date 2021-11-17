<?php

declare(strict_types=1);

namespace Cheeper\Application\Query\Timeline;

interface TimelineReadLayer
{
    public function byAuthorId(string $authorId, int $offset, int $size): array;
}