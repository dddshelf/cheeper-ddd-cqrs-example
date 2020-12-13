<?php

namespace CheeperHexagonal;

class PostId
{
    public function __construct(
        private int $id
    ) { }

    public function id(): int
    {
        return $this->id;
    }
}
