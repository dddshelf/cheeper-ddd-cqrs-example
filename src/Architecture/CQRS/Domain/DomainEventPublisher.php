<?php

namespace Architecture\CQRS\Domain;

class DomainEventPublisher
{
    /** @var Subscriber[] */
    private array $subscribers = [];
    private static ?DomainEventPublisher $instance = null;
    private int $id = 0;

    public static function instance(): self
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    public function subscribe(Subscriber $aDomainEventSubscriber): int
    {
        $id = $this->id;
        $this->subscribers[$id] = $aDomainEventSubscriber;
        $this->id++;

        return $id;
    }

    public function ofId(int $id): ?Subscriber
    {
        return isset($this->subscribers[$id]) ? $this->subscribers[$id] : null;
    }

    public function unsubscribe(int $id): void
    {
        unset($this->subscribers[$id]);
    }

    public function publish(DomainEvent $aDomainEvent): void
    {
        foreach ($this->subscribers as $aSubscriber) {
            if ($aSubscriber->isSubscribedTo($aDomainEvent)) {
                $aSubscriber->handle($aDomainEvent);
            }
        }
    }
}
