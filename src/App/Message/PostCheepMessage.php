<?php

declare(strict_types=1);

namespace App\Message;

//snippet post-cheep-message
final class PostCheepMessage
{
    private string $cheepId;
    private string $authorId;
    private string $message;

    public function __construct(string $cheepId, string $authorId, string $message)
    {
        $this->cheepId = $cheepId;
        $this->authorId = $authorId;
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
