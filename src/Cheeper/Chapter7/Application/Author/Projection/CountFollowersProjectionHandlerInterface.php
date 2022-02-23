<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Projection;

interface CountFollowersProjectionHandlerInterface
{
    public function __invoke(CountFollowersProjection $projection): void;
}
