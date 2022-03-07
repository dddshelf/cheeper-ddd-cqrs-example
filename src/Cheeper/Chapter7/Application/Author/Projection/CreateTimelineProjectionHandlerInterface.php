<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Projection;

interface CreateTimelineProjectionHandlerInterface
{
    public function __invoke(CreateTimelineProjection $projection): void;
}
