<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\DomainModel\Cheep;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\Chapter4\DomainModel\TriggerEventsTrait;
use DateTimeImmutable;
use DateTimeInterface;
use RuntimeException;

//snippet cheep
final class Cheep
{
    use TriggerEventsTrait;

    private DateTimeInterface $date;

    public static function compose(
        CheepId $cheepId,
        AuthorId $authorId,
        CheepMessage $message
    ): self {
        return new self(
            $cheepId->id(),
            $authorId->id(),
            $message->message()
        );
    }

    private function __construct(
        private string $cheepId,
        private string $authorId,
        private string $message,
    )
    {
        $this->date = new DateTimeImmutable();
        $this->setMessage($message);
        $this->notifyDomainEvent(
            CheepPosted::fromCheep($this)
        );
    }

    private function setMessage(string $message): void
    {
        if (empty($message)) {
            throw new RuntimeException('Message cannot be empty');
        }

        $this->message = $message;
    }

    public function authorId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    public function message(): CheepMessage
    {
        return CheepMessage::write($this->message);
    }

    public function date(): DateTimeInterface
    {
        return $this->date;
    }

    public function id(): CheepId
    {
        return CheepId::fromString($this->cheepId);
    }

    final public function recomposeWith(CheepMessage $cheepMessage): void
    {
        $this->setMessage($cheepMessage->message());
    }
}
//end-snippet
