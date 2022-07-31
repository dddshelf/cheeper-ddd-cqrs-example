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
    private string | UuidInterface | null $authorId = null;
    private string | UuidInterface | null $cheepId = null;
    private string $cheepMessage;

    private function __construct()
    {
    }

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

    public function fromAuthorId(string|UuidInterface $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }

    public function withCheepIdOf(string|UuidInterface $cheepId): self
    {
        $this->cheepId = $cheepId;
        
        return $this;
    }

    public function withAMessage(string $message): self
    {
        $this->cheepMessage = $message;

        return $this;
    }

    public function build(): Cheep
    {
        return Cheep::compose(
            AuthorTestDataBuilder::anAuthorIdentity($this->authorId),
            self::aCheepIdentity($this->cheepId),
            CheepMessage::write($this->cheepMessage)
        );
    }
}