<?php declare(strict_types=1);

namespace Architecture\CQRS\Domain;

use Ramsey\Uuid\Uuid;

class CategoryId
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function create(): CategoryId
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function id(): string
    {
        return $this->id;
    }
}
