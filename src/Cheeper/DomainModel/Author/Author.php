<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\FollowId;
use Cheeper\DomainModel\TriggerEventsTrait;
use DateTimeImmutable;
use InvalidArgumentException;

final class Author
{
    use TriggerEventsTrait;

    protected function __construct(
        protected string             $authorId,
        protected string             $userName,
        protected string             $email,
        protected ?string            $name = null,
        protected ?string            $biography = null,
        protected ?string            $location = null,
        protected ?string            $website = null,
        protected ?DateTimeImmutable $birthDate = null,
    ) {
        $this->setName($name);
        $this->setBiography($biography);
        $this->setLocation($location);

        $this->notifyDomainEvent(
            $this->buildNewAuthorSignedDomainEvent()
        );
    }

    protected function buildNewAuthorSignedDomainEvent(): NewAuthorSigned
    {
        return NewAuthorSigned::fromAuthor($this);
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
    ): static {
        return new static(
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

    protected function setName(?string $name): void
    {
        $this->name = $this->checkIsNotNull($name, 'Name cannot be empty');
    }

    protected function setBiography(?string $biography): void
    {
        $this->biography = $this->checkIsNotNull($biography, 'Biography cannot be empty');
    }

    protected function setLocation(?string $location): void
    {
        $this->location = $this->checkIsNotNull($location, 'Location cannot be empty');
    }

    public function authorId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    public function userId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    public function userName(): UserName
    {
        return UserName::pick($this->userName);
    }

    public function email(): EmailAddress
    {
        return EmailAddress::from($this->email);
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function biography(): ?string
    {
        return $this->biography;
    }

    public function location(): ?string
    {
        return $this->location;
    }

    public function website(): ?Website
    {
        return $this->website !== null ? Website::fromString($this->website) : null;
    }

    public function birthDate(): ?BirthDate
    {
        return $this->birthDate !== null ? BirthDate::fromString($this->birthDate->format('Y-m-d')) : null;
    }

    public function followAuthorId(AuthorId $toFollow): Follow
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
            throw new InvalidArgumentException($errorMessage);
        }

        return $value;
    }
}
