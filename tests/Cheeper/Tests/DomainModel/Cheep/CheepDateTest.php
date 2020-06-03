<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Cheep;

use Cheeper\DomainModel\Cheep\CheepDate;
use PHPUnit\Framework\TestCase;

final class CheepDateTest extends TestCase
{
    /** @test */
    public function throwsAnExceptionIfInvalidDateIsGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new CheepDate('test');
    }
}
