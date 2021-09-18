<?php

declare(strict_types=1);

namespace CheeperHexagonal;

final class PostId
{
    public function __construct(
        private int $id
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }
}
