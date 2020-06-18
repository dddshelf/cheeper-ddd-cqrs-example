<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Assert\Assertion;
use function Functional\filter;

class Author
{
    private AuthorId $authorId;
    private UserName $userName;
    private EmailAddress $email;
    private ?string $name;
    private ?string $biography;
    private ?string $location;
    private ?Website $website;
    private ?BirthDate $birthDate;
    /** @var AuthorId[]  */
    private array $following = [];

    private function __construct(
        AuthorId $authorId,
        UserName $userName,
        EmailAddress $email,
        ?string $name,
        ?string $biography,
        ?string $location,
        ?Website $website,
        ?BirthDate $birthDate
    ) {
        $this->authorId = $authorId;
        $this->userName = $userName;
        $this->email = $email;
        $this->setName($name);
        $this->setBiography($biography);
        $this->setLocation($location);
        $this->website = $website;
        $this->birthDate = $birthDate;
    }

    public static function signUp(
        AuthorId $authorId,
        UserName $userName,
        EmailAddress $email,
        ?string $name,
        ?string $biography,
        ?string $location,
        ?Website $website,
        ?BirthDate $birthDate
    ): self
    {
        return new self(
            $authorId,
            $userName,
            $email,
            $name,
            $biography,
            $location,
            $website,
            $birthDate
        );
    }

    private function setName(?string $name): void
    {
        if ($name !== null && empty($name)) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        $this->name = $name;
    }

    private function setBiography(?string $biography): void
    {
        if ($biography !== null && empty($biography)) {
            throw new \InvalidArgumentException('Biography cannot be empty');
        }

        $this->biography = $biography;
    }

    private function setLocation(?string $location): void
    {
        if ($location !== null && empty($location)) {
            throw new \InvalidArgumentException('Location cannot be empty');
        }

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

    public function email(): EmailAddress
    {
        return $this->email;
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
        return $this->website;
    }

    public function birthDate(): ?BirthDate
    {
        return $this->birthDate;
    }

    public function follow(AuthorId $followed): void
    {
        $alreadyFollowsUser = count(
            filter(
                $this->following,
                fn (AuthorId $authorId) => $authorId->equals($followed)
            )
        ) > 0;

        if ($alreadyFollowsUser) {
            return;
        }

        $this->following[] = $followed;
    }

    public function following(): array
    {
        return $this->following;
    }
}
