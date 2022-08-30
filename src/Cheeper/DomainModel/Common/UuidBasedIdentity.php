<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Common;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Stringable;

/** @psalm-immutable  */
abstract class UuidBasedIdentity extends ValueObject implements Stringable
{
    /** @psalm-param non-empty-string $id */
    final private function __construct(
        public readonly string $id
    ) {
    }

    /**
     * @psalm-param non-empty-string $uuid
     *
     * @psalm-pure
     */
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

    /** @psalm-pure */
    public static function fromUuid(UuidInterface $uuid): static
    {
        return new static($uuid->toString());
    }

    final public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
