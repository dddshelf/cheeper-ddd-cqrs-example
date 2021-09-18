<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\CQRS\Domain\Projection;

//snippet projector
final class Projector
{
    /** @var Projection[] */
    private array $projections = [];

    /** @param Projection[] $projections */
    public function register(array $projections): void
    {
        foreach ($projections as $projection) {
            $this->projections[$projection->listensTo()] = $projection;
        }
    }

    /** @param DomainEvent[] $events */
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
