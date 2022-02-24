<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Projection;

interface CreateFollowersCounterProjectionHandlerInterface
{
    public function __invoke(CreateFollowersCounterProjection $projection): void;
}
