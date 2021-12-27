<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application;

use Symfony\Component\Uid\Uuid;

//snippet message-id
class MessageId
{
    private string $value;

    final private function __construct()
    {
    }

    /** @return static */
    public static function fromUuidRfc4122AsString(string $uuidAsString): self
    {
        self::checkIsValidUuid($uuidAsString);

        return (new static)
            ->setValue($uuidAsString);
    }

    /** @return static */
    final public static function nextId(): self
    {
        return static::fromUuidRfc4122AsString(
            Uuid::v4()->toRfc4122()
        );
    }

    /** @return $this */
    private function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    private static function checkIsValidUuid(string $uuidAsString): void
    {
        if (!Uuid::isValid($uuidAsString)) {
            throw new \InvalidArgumentException('The value does not represent a valid identifier based in Uuid');
        }
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function id(): Uuid
    {
        return Uuid::fromRfc4122($this->value);
    }
}
//end-snippet
