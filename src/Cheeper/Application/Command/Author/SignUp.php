<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

//snippet sign-up
final class SignUp
{
    private string $authorId;
    private string $userName;
    private string $email;
    private ?string $name;
    private ?string $biography;
    private ?string $location;
    private ?string $website;
    private ?string $birthDate;

    public function __construct(
        string $authorId,
        string $userName,
        string $email,
        ?string $name,
        ?string $biography,
        ?string $location,
        ?string $website,
        ?string $birthDate
    ) {
        $this->authorId = $authorId;
        $this->userName = $userName;
        $this->email = $email;
        $this->name = $name;
        $this->biography = $biography;
        $this->location = $location;
        $this->website = $website;
        $this->birthDate = $birthDate;
    }

    //ignore
    public function authorId(): string
    {
        return $this->authorId;
    }

    public function userName(): string
    {
        return $this->userName;
    }

    public function email(): string
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

    public function website(): ?string
    {
        return $this->website;
    }

    public function birthDate(): ?string
    {
        return $this->birthDate;
    }
    //end-ignore
}
//end-snippet
