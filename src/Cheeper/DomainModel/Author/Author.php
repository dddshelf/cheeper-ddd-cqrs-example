<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\FollowId;
use Cheeper\DomainModel\TriggerEventsTrait;

class Author
{
    use TriggerEventsTrait;

    private function __construct(
        private string $authorId,
        private string $userName,
        private string $email,
        private ?string $name = null,
        private ?string $biography = null,
        private ?string $location = null,
        private ?string $website = null,
        private ?string $birthDate = null,
    ) {
        $this->setName($name);
        $this->setBiography($biography);
        $this->setLocation($location);

        $this->notifyDomainEvent(
            NewAuthorSigned::fromAuthor($this)
        );
    }

    public static function signUp(
        AuthorId $authorId,
        UserName $userName,
        EmailAddress $email,
        ?string $name = null,
        ?string $biography = null,
        ?string $location = null,
        ?Website $website = null,
        ?BirthDate $birthDate = null
    ): self {
        return new self(
            $authorId->toString(),
            $userName->userName(),
            $email->value(),
            $name,
            $biography,
            $location,
            $website?->toString(),
            $birthDate?->date()
        );
    }

    private function setName(?string $name): void
    {
        $this->name = $this->checkIsNotNull($name, 'Name cannot be empty');
    }

    private function setBiography(?string $biography): void
    {
        $this->biography = $this->checkIsNotNull($biography, 'Biography cannot be empty');
    }

    private function setLocation(?string $location): void
    {
        $this->location = $this->checkIsNotNull($location, 'Location cannot be empty');
    }

    final public function authorId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    final public function userId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    final public function userName(): UserName
    {
        return UserName::pick($this->userName);
    }

    final public function email(): EmailAddress
    {
        return EmailAddress::from($this->email);
    }

    final public function name(): ?string
    {
        return $this->name;
    }

    final public function biography(): ?string
    {
        return $this->biography;
    }

    final public function location(): ?string
    {
        return $this->location;
    }

    final public function website(): ?Website
    {
        return $this->website !== null ? Website::fromString($this->website) : null;
    }

    final public function birthDate(): ?BirthDate
    {
        return $this->birthDate !== null ? BirthDate::fromString($this->birthDate) : null;
    }

    final public function followAuthorId(AuthorId $toFollow): Follow
    {
        return Follow::fromAuthorToAuthor(
            followId: FollowId::nextIdentity(),
            fromAuthorId: $this->authorId(),
            toAuthorId: $toFollow
        );
    }

    private function checkIsNotNull(?string $value, string $errorMessage): ?string
    {
        if ('' === $value) {
            throw new \InvalidArgumentException($errorMessage);
        }

        return $value;
    }
}
