<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

use Ramsey\Uuid\Uuid;
use Stringable;

class PostId implements Stringable
{
    public function __construct(
        private string $id
    ) {
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
