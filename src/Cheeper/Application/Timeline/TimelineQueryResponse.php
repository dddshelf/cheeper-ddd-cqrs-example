<?php

declare(strict_types=1);

namespace Cheeper\Application\Timeline;

use App\Dto\CheepDto;
use Cheeper\Application\QueryResponse;

/** @psalm-immutable */
final class TimelineQueryResponse implements QueryResponse
{
    /**
     * @psalm-param list<CheepDto> $timeline
     * @param CheepDto[] $timeline
     */
    public function __construct(
        public readonly array $timeline
    ) {
    }
}
