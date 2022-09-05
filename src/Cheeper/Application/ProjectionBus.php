<?php

declare(strict_types=1);

namespace Cheeper\Application;

interface ProjectionBus
{
    public function project(Projection $projection): void;
}
