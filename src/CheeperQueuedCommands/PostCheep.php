<?php

declare(strict_types=1);

namespace CheeperQueuedCommands;

use Cheeper\Application\Command\AsyncCommand;

final class PostCheep implements AsyncCommand
{
    private function __construct(
        private string $authorId,
        private string $cheepId,
        private string $message
    ) { }

    /** @param array{author_id: string, cheep_id: string, message: string} $array */
    public static function fromArray(array $array): self
    {
        return new static(
            $array['author_id'],
            $array['cheep_id'],
            $array['message'],
        );
    }

    public function cheepId(): string
    {
        return $this->cheepId;
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function message(): string
    {
        return $this->message;
    }
}
