<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application;

//snippet message-trait
trait MessageTrait
{
    protected ?MessageId $messageId = null;
    protected ?MessageId $replyToId = null;
    protected ?MessageId $correlationId = null;

    /**
     * @param MessageId|null $messageId
     * @param MessageId|null $replyToId
     * @param MessageId|null $correlationId
     * @return static
     */
    public function stampIds(
        ?MessageId $messageId = null,
        ?MessageId $replyToId = null,
        ?MessageId $correlationId = null
    ): self {
        $this->messageId = $messageId;
        $this->replyToId = $replyToId;
        $this->correlationId = $correlationId;

        return $this;
    }

    public function stampAsNewMessage(): self
    {
        $messageId = MessageId::nextId();

        return $this->stampIds(
            $messageId,
            null,
            $messageId,
        );
    }

    public function stampAsResponse(
        ?MessageId $replyTo,
        ?MessageId $correlationId
    ): self {
        return $this->stampIds(
            MessageId::nextId(),
            $replyTo,
            $correlationId,
        );
    }

    /**
     * @param MessageTrait $message
     * @return static
     */
    public function stampAsResponseTo($message): self
    {
        return $this->stampIds(
            MessageId::nextId(),
            $message->messageId(),
            $message->messageCorrelationId()
        );
    }

    public function messageId(): ?MessageId
    {
        return $this->messageId;
    }

    public function messageReplyId(): ?MessageId
    {
        return $this->replyToId;
    }

    public function messageCorrelationId(): ?MessageId
    {
        return $this->correlationId;
    }
}
//end-snippet