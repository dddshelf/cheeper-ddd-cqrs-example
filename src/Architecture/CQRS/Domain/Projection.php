<?php declare(strict_types=1);

namespace Architecture\CQRS\Domain;

/**
 * @template T of DomainEvent
 */
//snippet projection
interface Projection
{
    public function listensTo(): string;
    /** @param T $event */
    public function project(DomainEvent $event): void;
}
//end-snippet
