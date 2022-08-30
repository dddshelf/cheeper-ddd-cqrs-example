<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Cheep;

use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use Ramsey\Uuid\UuidInterface;

final class CheepTestDataBuilder
{
    /** @psalm-var non-empty-string|UuidInterface|null  */
    private string | UuidInterface | null $authorId = null;
    /** @psalm-var non-empty-string|UuidInterface|null  */
    private string | UuidInterface | null $cheepId = null;
    /** @psalm-var non-empty-string|null  */
    private string|null $cheepMessage = null;

    private function __construct()
    {
    }

    /** @psalm-param non-empty-string|UuidInterface|null $aCheepId */
    public static function aCheepIdentity(string | UuidInterface | null $aCheepId = null): CheepId
    {
        if ($aCheepId && is_string($aCheepId)) {
            return CheepId::fromString($aCheepId);
        }

        if ($aCheepId) {
            return CheepId::fromUuid($aCheepId);
        }

        return CheepId::nextIdentity();
    }

    public static function aCheep(): self
    {
        return new self();
    }

    /** @psalm-param non-empty-string|UuidInterface $authorId */
    public function fromAuthorId(string|UuidInterface $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }

    /** @psalm-param non-empty-string|UuidInterface $cheepId */
    public function withCheepIdOf(string|UuidInterface $cheepId): self
    {
        $this->cheepId = $cheepId;
        
        return $this;
    }

    /** @psalm-param non-empty-string $message */
    public function withAMessage(string $message): self
    {
        $this->cheepMessage = $message;

        return $this;
    }

    public function build(): Cheep
    {
        if (null === $this->cheepMessage) {
            throw new \InvalidArgumentException("Cheep message cannot be null");
        }

        return Cheep::compose(
            AuthorTestDataBuilder::anAuthorIdentity($this->authorId),
            self::aCheepIdentity($this->cheepId),
            CheepMessage::write($this->cheepMessage)
        );
    }
}