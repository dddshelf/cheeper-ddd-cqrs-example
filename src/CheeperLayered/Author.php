<?php

namespace CheeperLayered;

//snippet author
class Author
{
    private ?int $id = null;
    private ?string $website = null;
    private ?string $bio = null;
    private string $username;

    private function __construct(string $username)
    {
        $this->setUsername($username);
    }

    public static function create(string $username): self
    {
        return new static($username);
    }

    public function setUsername(string $username): void
    {
        if (empty($username)) {
            throw new \RuntimeException('Username cannot be empty');
        }

        $this->username = $username;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function setWebsite(?string $website): void
    {
        if ($website && !filter_var($website, FILTER_VALIDATE_URL)) {
            throw new \RuntimeException('Website must be a valid URL');
        }

        $this->website = $website;
    }

    public function website(): ?string
    {
        return $this->website;
    }

    public function setBio(?string $bio): void
    {
        if ($bio && empty($bio)) {
            throw new \RuntimeException('Bio cannot be empty');
        }

        $this->bio = $bio;
    }

    public function bio(): ?string
    {
        return $this->bio;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function compose(string $message): Cheep
    {
        if (!$this->id) {
            throw new \RuntimeException('Author id has not been assigned yet');
        }
        
        return Cheep::compose($this->id, $message);
    }
}
//end-snippet
