<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application;

//snippet query-bus
interface QueryBus
{
    public function query(Query $query): mixed;
}
//end-snippet
