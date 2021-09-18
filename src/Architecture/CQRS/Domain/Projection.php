<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

//snippet projection
interface Projection
{
    public function listensTo(): string;
    public function project(DomainEvent $event): void;
}
//end-snippet
