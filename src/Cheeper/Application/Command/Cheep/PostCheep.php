<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Cheep;

//snippet post-cheep
final class PostCheep
{
    private string $cheepId;
    private string $authorId;
    private string $message;

    /** @param array{author_id: string, cheep_id: string, message: string} $array */
    public static function fromArray(array $array): self
    {
        return new static(
            $array['author_id'],
            $array['cheep_id'],
            $array['message'],
        );
    }

    private function __construct(string $authorId, string $cheepId, string $message)
    {
        $this->authorId = $authorId;
        $this->cheepId = $cheepId;
        $this->message = $message;
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
