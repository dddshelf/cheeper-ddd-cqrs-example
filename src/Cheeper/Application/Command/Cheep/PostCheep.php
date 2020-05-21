<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Cheep;

use Cheeper\Application\Command\SyncCommand;
use Symfony\Component\HttpFoundation\Request;

//snippet post-cheep
final class PostCheep implements SyncCommand
{
    private string $cheepId;
    private string $authorId;
    private string $message;

    public static function fromRequest(Request $request): self
    {
        return new static(
            (string) $request->get('author_id'),
            (string) $request->get('cheep_id'),
            (string) $request->get('message')
        );
    }

    public static function fromJsonPayload(array $array): self
    {
        return new static(
            (string) $array['author_id'],
            (string) $array['cheep_id'],
            (string) $array['message'],
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
