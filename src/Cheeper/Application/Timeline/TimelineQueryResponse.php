<?php

declare(strict_types=1);

namespace Cheeper\Application\Timeline;

use Cheeper\Application\QueryResponse;
use Cheeper\DomainModel\Cheep\Cheep;

/** @psalm-immutable */
final class TimelineQueryResponse implements QueryResponse
{
    /**
     * @psalm-param list<Cheep> $timeline
     * @param Cheep[] $timeline
     */
    public function __construct(
        public readonly array $timeline
    ) {
    }
}
