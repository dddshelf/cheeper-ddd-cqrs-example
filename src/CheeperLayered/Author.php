<?php

namespace CheeperLayered;

//snippet author
class Author
{
    private ?int $id = null;
    private string $username;

    public static function create(string $username): self
    {
        return new static($username);
    }

    private function __construct(string $username)
    {
        $this->setUsername($username);
    }

    private function setUsername(string $username): void
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
