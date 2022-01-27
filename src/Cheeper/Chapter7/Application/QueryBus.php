<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application;

//snippet query-bus
interface QueryBus
{
    public function query(Query $query): mixed;
}
//end-snippet
