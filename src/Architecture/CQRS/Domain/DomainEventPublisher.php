<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

final class DomainEventPublisher
{
    /** @var Subscriber[] */
    private array $subscribers = [];
    private static ?DomainEventPublisher $instance = null;
    private int $id = 0;

    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
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
        return $this->subscribers[$id] ?? null;
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
