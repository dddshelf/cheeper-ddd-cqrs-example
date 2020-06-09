<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
//snippet signup-builder
final class SignUpBuilder
{
    private string $authorId;
    private string $userName;
    private string $name;
    private string $biography;
    private string $location;
    private string $website;
    private string $birthDate;

    private function __construct()
    {
    }

    public static function create(): self
    {
        $builder = new self();
        $builder->authorId = Uuid::uuid4()->toString();

        return $builder;
    }

    /** @param string|UuidInterface $authorId */
    public function withAuthorId($authorId): self
    {
        $this->authorId = (string)$authorId;

        return $this;
    }

    //ignore
    public function withUserName(string $userName): self
    {
        $this->userName = $userName;

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

    public function withWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function withBirthDate(string $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }
    //end-ignore

    public function build(): SignUp
    {
        if (!$this->authorId || !$this->userName || !$this->name || !$this->biography || !$this->location || !$this->website || !$this->birthDate) {
            throw new \BadMethodCallException('Parameters needed to build the SignUp command are incorrect');
        }

        return new SignUp(
            $this->authorId,
            $this->userName,
            $this->name,
            $this->biography,
            $this->location,
            $this->website,
            $this->birthDate
        );
    }
}
//end-snippet
