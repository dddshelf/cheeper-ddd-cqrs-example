<?php

namespace Architecture\ES\Domain;

use PHPUnit\Framework\TestCase;

class EventStreamTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBuildAnEventStream(): void
    {
        $stream = new EventStream('irrelevant', [1, 2, 3]);

        $collected = [];
        foreach ($stream as $key => $event) {
            $collected[$key] = $event;
        }

        $this->assertEquals('irrelevant', $stream->getAggregateId());
        $this->assertEquals($collected, [1, 2, 3]);
    }
}
