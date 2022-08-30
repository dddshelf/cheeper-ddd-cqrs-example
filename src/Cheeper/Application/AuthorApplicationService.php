<?php

declare(strict_types=1);

namespace Cheeper\Application;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorAlreadyExists;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;

final class AuthorApplicationService
{
    public function __construct(
        private readonly AuthorRepository $authorRepository
    ) {
    }

    /**
     * @psalm-param non-empty-string $id
     * @psalm-param non-empty-string $username
     * @psalm-param non-empty-string $email
     * @psalm-param non-empty-string|null $name
     * @psalm-param non-empty-string|null $biography
     * @psalm-param non-empty-string|null $location
     * @psalm-param non-empty-string|null $website
     * @psalm-param non-empty-string|null $birthDate
     */
    public function signUp(
        string      $id,
        string      $username,
        string      $email,
        string|null $name,
        string|null $biography,
        string|null $location,
        string|null $website,
        string|null $birthDate,
    ): Author {
        $authorUserName = UserName::pick($username);
        $authorId = AuthorId::fromString($id);
        $authorEmail = EmailAddress::from($email);

        $author = $this->authorRepository->ofUserName($authorUserName);
        $this->assertAuthorIsNotNull($author, $authorUserName);

        $authorWebsite = $this->getWebsite($website);
        $authorBirthDate = $this->getBirthDate($birthDate);

        $author = Author::signUp(
            $authorId,
            $authorUserName,
            $authorEmail,
            $name,
            $biography,
            $location,
            $authorWebsite,
            $authorBirthDate
        );

        $this->authorRepository->add($author);

        return $author;
    }

    /** @return list<Author> */
    public function getAuthors(): array
    {
        return $this->authorRepository->all();
    }

    /** @psalm-assert Author $author */
    private function assertAuthorIsNotNull(Author|null $author, UserName $userName): void
    {
        if (null !== $author) {
            throw AuthorAlreadyExists::withUserNameOf($userName);
        }
    }

    /**
     * @psalm-param non-empty-string $inputBirthDate
     */
    private function getBirthDate(string|null $inputBirthDate): ?BirthDate
    {
        return null !== $inputBirthDate ? BirthDate::fromString($inputBirthDate) : null;
    }

    /**
     * @psalm-param non-empty-string $inputWebsite
     */
    private function getWebsite(string|null $inputWebsite): ?Website
    {
        return null !== $inputWebsite ? Website::fromString($inputWebsite) : null;
    }
}
