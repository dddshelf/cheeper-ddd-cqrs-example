<?php declare(strict_types=1);

namespace Architecture\CQRS\Domain;

use Ramsey\Uuid\Uuid;

class PostId
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function create(): PostId
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->id;
    }
}
