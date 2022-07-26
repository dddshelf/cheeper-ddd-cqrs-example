<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Clock\Clock;
use DateTimeInterface;

final class Cheep
{
    private ?int $id = null;
    private string $message;
    private DateTimeInterface $date;

    public static function compose(int $authorId, string $message): self
    {
        return new self($authorId, $message);
    }

    private function __construct(private readonly int $authorId, string $message)
    {
        $this->date = Clock::instance()->now();
        $this->setMessage($message);
    }

    private function setMessage(string $message): void
    {
        if (empty($message)) {
            throw new \RuntimeException('Message cannot be empty');
        }

        $this->message = $message;
    }

    public function authorId(): int
    {
        return $this->authorId;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function date(): DateTimeInterface
    {
        return $this->date;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }
}
