<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

//snippet sign-up-builder
final class SignUpBuilder
{
    private string $authorId;
    private string $userName;
    private ?string $name = null;
    private ?string $biography = null;
    private ?string $location = null;
    private ?string $website = null;
    private ?string $birthDate = null;

    private function __construct(string $authorId, string $userName)
    {
        $this->authorId = $authorId;
        $this->userName = $userName;
    }

    public static function create(string $authorId, string $userName): self
    {
        return new self($authorId, $userName);
    }

    public function username(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function biography(string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    public function location(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function website(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function birthDate(string $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function build(): SignUp
    {
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
