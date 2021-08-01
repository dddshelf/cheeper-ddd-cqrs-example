<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\FollowId;
use function Functional\filter;

class Author
{
    /** @var AuthorId[]  */
    private array $following = [];

    private function __construct(
        private AuthorId $authorId,
        private UserName $userName,
        private EmailAddress $email,
        private ?string $name,
        private ?string $biography,
        private ?string $location,
        private ?Website $website,
        private ?BirthDate $birthDate,
    ) {
        $this->setName($name);
        $this->setBiography($biography);
        $this->setLocation($location);
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
        if (null !== $name && '' === $name) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        $this->name = $name;
    }

    private function setBiography(?string $biography): void
    {
        if ($biography !== null && '' === $biography) {
            throw new \InvalidArgumentException('Biography cannot be empty');
        }

        $this->biography = $biography;
    }

    private function setLocation(?string $location): void
    {
        if ($location !== null && '' === $location) {
            throw new \InvalidArgumentException('Location cannot be empty');
        }

        $this->location = $location;
    }

    final public function userId(): AuthorId
    {
        return $this->authorId;
    }

    final public function userName(): UserName
    {
        return $this->userName;
    }

    final public function email(): EmailAddress
    {
        return $this->email;
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
        return $this->website;
    }

    final public function birthDate(): ?BirthDate
    {
        return $this->birthDate;
    }

    final public function follow(AuthorId $followed): void
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

    final public function followAuthorId(AuthorId $toFollow): Follow
    {
        return new Follow(
            FollowId::nextIdentity(),
            $this->authorId,
            $toFollow
        );
    }

    /** @return AuthorId[] */
    final public function following(): array
    {
        return $this->following;
    }
}
