<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Cheep\Command;

use Cheeper\Chapter7\Application\Command\Command;
use Cheeper\Chapter7\Application\MessageTrait;

//snippet post-cheep
final class PostCheepCommand implements Command
{
    use MessageTrait;

    private function __construct(
        private string $cheepId,
        private string $authorId,
        private string $message,
    ) {
        $this->stampAsNewMessage();
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['cheep_id'] ?? '',
            $array['author_id'] ?? '',
            $array['message'] ?? '',
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
