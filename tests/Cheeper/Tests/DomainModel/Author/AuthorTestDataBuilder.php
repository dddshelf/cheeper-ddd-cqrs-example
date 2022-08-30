<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Author;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class AuthorTestDataBuilder
{
    /** @psalm-var non-empty-string  */
    private string $userName = "irrelevant";

    /** @psalm-var non-empty-string  */
    private string $email = "test@email.com";

    /** @psalm-var non-empty-string|null  */
    private string|null $name = null;

    /** @psalm-var non-empty-string|null  */
    private string|null $biography = null;

    /** @psalm-var non-empty-string|null  */
    private string|null $location = null;

    /** @psalm-var non-empty-string|null  */
    private string|null $website = null;

    /** @psalm-var non-empty-string|null  */
    private string|null $birthDate = null;

    private function __construct()
    {
    }

    public static function anAuthor(): self
    {
        return new self();
    }

    /** @psalm-param non-empty-string|UuidInterface|null $anAuthorId */
    public static function anAuthorIdentity(string | UuidInterface | null $anAuthorId = null): AuthorId
    {
        if ($anAuthorId && is_string($anAuthorId)) {
            return AuthorId::fromString($anAuthorId);
        }

        if ($anAuthorId) {
            return AuthorId::fromUuid($anAuthorId);
        }

        return AuthorId::nextIdentity();
    }

    /** @psalm-param non-empty-string $userName */
    public function withUserNameOf(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /** @psalm-param non-empty-string $email */
    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /** @psalm-param non-empty-string $name */
    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** @psalm-param non-empty-string $biography */
    public function withBiography(string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    /** @psalm-param non-empty-string $location */
    public function withLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function build(): Author
    {
        return Author::signUp(
            AuthorId::nextIdentity(),
            UserName::pick($this->userName),
            EmailAddress::from($this->email),
            $this->name,
            $this->biography,
            $this->location,
            $this->website ? Website::fromString($this->website) : null,
            $this->birthDate ? BirthDate::fromString($this->birthDate) : null,
        );
    }
}