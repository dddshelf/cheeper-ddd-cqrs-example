<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Cheep;

use JetBrains\PhpStorm\Pure;

//snippet post-cheep
final class PostCheep
{
    private function __construct(
        private string $cheepId,
        private string $authorId,
        private string $message,
    ) {
    }

    /** @param array{author_id: string, cheep_id: string, message: string} $array */
    public static function fromArray(array $array): self
    {
        return new static(
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
