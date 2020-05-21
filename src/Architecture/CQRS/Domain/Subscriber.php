<?php

namespace Architecture\CQRS\Domain;

interface Subscriber
{
    public function isSubscribedTo(DomainEvent $event): bool;
    public function handle(DomainEvent $event): void;
}
