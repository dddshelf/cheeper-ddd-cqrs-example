<?php

namespace Architecture\CQRS\Infrastructure\Projection;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\CQRS\Domain\Projection;

/** @template T of DomainEvent */
//snippet projector
class Projector
{
    /** @var Projection<T>[] */
    private array $projections = [];

    /** @param Projection<T>[] $projections */
    public function register(array $projections): void
    {
        foreach ($projections as $projection) {
            $this->projections[$projection->listensTo()] = $projection;
        }
    }

    /** @param T[] $events */
    public function project(array $events): void
    {
        foreach ($events as $event) {
            if (isset($this->projections[get_class($event)])) {
                $this->projections[get_class($event)]->project($event);
            }
        }
    }
}
//end-snippet
