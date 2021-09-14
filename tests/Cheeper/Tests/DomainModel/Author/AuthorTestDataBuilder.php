<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Author;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\Website;
use Cheeper\DomainModel\Author\BirthDate;

use Ramsey\Uuid\Uuid;

final class AuthorTestDataBuilder
{
    private AuthorId $authorId;
    private UserName $userName;
    private EmailAddress $email;
    private ?string $name = null;
    private ?string $biography = null;
    private ?string $location = null;
    private ?Website $website = null;
    private ?BirthDate $birthDate = null;

    public static function anAuthor(): self
    {
        return new self();
    }

    private function __construct()
    {
        $this
            ->authorId(Uuid::uuid4()->toString())
            ->userName('johndoe')
            ->email('johndoe@example.com');
    }

    public function build(): Author
    {
        return Author::signUp(
            $this->authorId,
            $this->userName,
            $this->email,
            $this->name,
            $this->biography,
            $this->location,
            $this->website,
            $this->birthDate
        );
    }

    public function authorId(string $authorId): self
    {
        $this->authorId = AuthorId::fromString($authorId);

        return $this;
    }

    public function userName(string $userName): self
    {
        $this->userName = UserName::pick($userName);

        return $this;
    }

    public function email(string $emailAddress): self
    {
        $this->email = new EmailAddress($emailAddress);

        return $this;
    }
}
