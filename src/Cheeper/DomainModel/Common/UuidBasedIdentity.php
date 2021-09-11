<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Common;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class UuidBasedIdentity extends ValueObject
{
    protected string $idAsString;

    final private function __construct(
        protected string $id
    ) {
        $this->idAsString = $id;
    }

    public static function fromString(string $uuid): static
    {
        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException('The value does not represent a valid identifier based in Uuid');
        }

        return new static($uuid);
    }

    public static function nextIdentity(): static
    {
        return new static(Uuid::uuid4()->toString());
    }

    public static function fromUuid(UuidInterface $uuid): static
    {
        return new static($uuid->toString());
    }

    final public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    final public function toString(): string
    {
        return (string)$this->__toString();
    }

    public function __toString(): string
    {
        return $this->id;
    }

    final public function id(): string
    {
        return $this->id;
    }
}
