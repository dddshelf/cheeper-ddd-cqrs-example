<?php

declare(strict_types=1);

namespace Cheeper\Application;

/**
 * @template TQuery of Query
 * @template TQueryResponse of QueryResponse
 */
interface QueryBus
{
    /**
     * @psalm-param TQuery $query
     * @psalm-return TQueryResponse
     */
    public function askFor(Query $query): QueryResponse;
}
