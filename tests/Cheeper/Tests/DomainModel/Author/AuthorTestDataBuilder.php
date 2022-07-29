<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Author;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;

final class AuthorTestDataBuilder
{
    private string $userName = "irrelevant";
    private string $email = "test@email.com";
    private ?string $name = null;
    private ?string $biography = null;
    private ?string $location = null;
    private ?string $website = null;
    private ?string $birthDate = null;

    private function __construct()
    {
    }

    public static function anAuthor(): self
    {
        return new self();
    }

    public function withUserNameOf(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withBiography(string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

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