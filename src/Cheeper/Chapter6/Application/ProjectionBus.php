<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application;

//snippet projection-bus
interface ProjectionBus
{
    public function project(Projection $projection): void;
}
//end-snippet
