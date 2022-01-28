<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Application\Cheep\Command;

//snippet post-cheep
final class PostCheepCommand
{
    private function __construct(
        private string $cheepId,
        private string $authorId,
        private string $message,
    ) {
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['cheep_id'],
            $array['author_id'],
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
//end-snippet
