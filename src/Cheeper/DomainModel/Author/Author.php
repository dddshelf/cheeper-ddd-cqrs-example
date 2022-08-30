<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\FollowId;
use DateTimeImmutable;
use InvalidArgumentException;

/** @final */
class Author
{
    /**
     * @psalm-param non-empty-string $authorId
     * @psalm-param non-empty-string $userName
     * @psalm-param non-empty-string $email
     * @psalm-param non-empty-string|null $name
     * @psalm-param non-empty-string|null $biography
     * @psalm-param non-empty-string|null $location
     * @psalm-param non-empty-string|null $website
     */
    private function __construct(
        private string                 $authorId,
        private string                 $userName,
        private string                 $email,
        private string|null            $name = null,
        private string|null            $biography = null,
        private string|null            $location = null,
        private string|null            $website = null,
        private DateTimeImmutable|null $birthDate = null,
    ) {
        $this->setName($name);
        $this->setBiography($biography);
        $this->setLocation($location);
    }

    /**
     * @psalm-param non-empty-string|null $name
     * @psalm-param non-empty-string|null $biography
     * @psalm-param non-empty-string|null $location
     */
    public static function signUp(
        AuthorId     $authorId,
        UserName     $userName,
        EmailAddress $email,
        string|null  $name = null,
        string|null  $biography = null,
        string|null  $location = null,
        Website|null $website = null,
        BirthDate|null $birthDate = null,
    ): self {
        return new self(
            $authorId->id,
            $userName->userName,
            $email->value,
            $name,
            $biography,
            $location,
            $website?->uri,
            $birthDate?->date()
        );
    }

    protected function setName(string|null $name): void
    {
        $this->name = $this->assertNotEmpty($name, 'Name cannot be empty');
    }

    protected function setBiography(string|null $biography): void
    {
        $this->biography = $this->assertNotEmpty($biography, 'Biography cannot be empty');
    }

    protected function setLocation(string|null $location): void
    {
        $this->location = $this->assertNotEmpty($location, 'Location cannot be empty');
    }

    public function authorId(): AuthorId
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

    /** @psalm-return non-empty-string */
    public function name(): string|null
    {
        return $this->name;
    }

    /** @psalm-return non-empty-string */
    public function biography(): string|null
    {
        return $this->biography;
    }

    /** @psalm-return non-empty-string */
    public function location(): string|null
    {
        return $this->location;
    }

    public function website(): Website|null
    {
        return $this->website !== null ? Website::fromString($this->website) : null;
    }

    public function birthDate(): BirthDate|null
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

    /**
     * @psalm-assert non-empty-string|null $value
     * @psalm-return non-empty-string|null
     */
    private function assertNotEmpty(string|null $value, string $errorMessage): string|null
    {
        if ('' === $value) {
            throw new InvalidArgumentException($errorMessage);
        }

        return $value;
    }
}
