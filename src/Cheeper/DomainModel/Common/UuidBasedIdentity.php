<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Common;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class UuidBasedIdentity extends ValueObject
{
    protected string $id;
    protected string $idAsString;

    final private function __construct(string $id)
    {
        $this->id = $this->idAsString = $id;
    }

    /** @return static */
    public static function fromString(string $uuid): self
    {
        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException('The value does not represent a valid identifier based in Uuid');
        }

        return new static($uuid);
    }

    /** @return static */
    public static function fromUuid(UuidInterface $uuid): self
    {
        return new static($uuid->toString());
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
