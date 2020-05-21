<?php

namespace CheeperLayered;

use PHPUnit\Framework\TestCase;

class CheepTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldNotCreateCheep(): void
    {
        $this->expectException(\RuntimeException::class);
        
        Cheep::compose(1, '');
    }

    /**
     * @test
     */
    public function itShouldCreateCheep(): void
    {
        $this->assertNotNull(Cheep::compose(1, 'A message'));
    }
}
