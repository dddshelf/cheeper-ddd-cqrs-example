<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

interface Subscriber
{
    public function isSubscribedTo(DomainEvent $event): bool;
    public function handle(DomainEvent $event): void;
}
