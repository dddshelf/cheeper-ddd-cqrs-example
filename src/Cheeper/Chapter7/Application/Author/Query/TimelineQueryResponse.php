<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Query;

final class TimelineQueryResponse
{
    public function __construct(
        public array $cheeps
    ) {
    }
}
