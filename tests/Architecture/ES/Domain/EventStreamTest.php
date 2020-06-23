<?php

namespace Architecture\ES\Domain;

use Cheeper\DomainModel\DomainEvent;
use PHPUnit\Framework\TestCase;

class EventStreamTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBuildAnEventStream(): void
    {
        $domainEvents = [
            new class implements DomainEvent{},
            new class implements DomainEvent{},
            new class implements DomainEvent{},
        ];

        $stream = new EventStream('irrelevant', $domainEvents);

        $collected = [];
        foreach ($stream as $key => $event) {
            $collected[$key] = $event;
        }

        $this->assertEquals('irrelevant', $stream->getAggregateId());
        $this->assertEquals($collected, $domainEvents);
    }
}
