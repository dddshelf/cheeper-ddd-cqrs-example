<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Common;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class UuidBasedIdentity extends ValueObject
{
    protected UuidInterface $id;
    protected UuidInterface $idAsString;

    private function __construct(UuidInterface $id)
    {
        $this->id = $this->idAsString = $id;
    }

    /** @return static */
    public static function fromString(string $uuid): self
    {
        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException('The value does not represent a valid identifier based in Uuid');
        }

        return new static(Uuid::fromString($uuid));
    }

    /** @return static */
    public static function fromUuid(UuidInterface $uuid): self
    {
        return new static($uuid);
    }

    public function equals(self $other): bool
    {
        return $this->id->equals($other->id);
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString()
    {
        return $this->id->toString();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }
}
