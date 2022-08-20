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

    public function signUp(
        string  $id,
        string  $username,
        string  $email,
        ?string $name,
        ?string $biography,
        ?string $location,
        ?string $website,
        ?string $birthDate,
    ): Author {
        $authorUserName = UserName::pick($username);
        $authorId = AuthorId::fromString($id);
        $authorEmail = EmailAddress::from($email);

        $author = $this->authorRepository->ofUserName($authorUserName);
        $this->checkAuthorDoesNotAlreadyExistByUsername($author, $authorUserName);

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

    public function getAuthors(): array
    {
        return $this->authorRepository->all();
    }

    private function checkAuthorDoesNotAlreadyExistByUsername(?Author $author, UserName $userName): void
    {
        if (null !== $author) {
            throw AuthorAlreadyExists::withUserNameOf($userName);
        }
    }

    private function getBirthDate(?string $inputBirthDate): ?BirthDate
    {
        return null !== $inputBirthDate ? BirthDate::fromString($inputBirthDate) : null;
    }

    private function getWebsite(?string $inputWebsite): ?Website
    {
        return null !== $inputWebsite ? Website::fromString($inputWebsite) : null;
    }
}
