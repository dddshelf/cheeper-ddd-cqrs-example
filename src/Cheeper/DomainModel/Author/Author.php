<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Assert\Assertion;
use function Functional\filter;

class Author
{
    private AuthorId $authorId;
    private UserName $userName;
    private string $name;
    private string $biography;
    private string $location;
    private Website $website;
    private BirthDate $birthDate;
    /** @var AuthorId[]  */
    private array $following = [];

    public function __construct(AuthorId $authorId, UserName $userName, string $name, string $biography, string $location, Website $website, BirthDate $birthDate)
    {
        $this->authorId = $authorId;
        $this->userName = $userName;
        $this->setName($name);
        $this->setBiography($biography);
        $this->setLocation($location);
        $this->website = $website;
        $this->birthDate = $birthDate;
    }

    public static function signUp(AuthorId $authorId, UserName $userName, string $name, string $biography, string $location, Website $website, BirthDate $birthDate): self
    {
        return new self($authorId, $userName, $name, $biography, $location, $website, $birthDate);
    }

    private function setName(string $name): void
    {
        Assertion::notEmpty($name);

        $this->name = $name;
    }

    private function setBiography(string $biography): void
    {
        Assertion::notEmpty($biography);

        $this->biography = $biography;
    }

    private function setLocation(string $location): void
    {
        Assertion::notEmpty($location);

        $this->location = $location;
    }

    public function userId(): AuthorId
    {
        return $this->authorId;
    }

    public function userName(): UserName
    {
        return $this->userName;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function biography(): string
    {
        return $this->biography;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function website(): Website
    {
        return $this->website;
    }

    public function birthDate(): BirthDate
    {
        return $this->birthDate;
    }

    public function follow(Author $followed): void
    {
        $alreadyFollowsUser = count(
            filter(
                $this->following,
                fn (AuthorId $authorId) => $authorId->equals($followed->userId())
            )
        ) > 0;

        if ($alreadyFollowsUser) {
            return;
        }

        $this->following[] = $followed->userId();
    }

    public function following(): array
    {
        return $this->following;
    }
}
