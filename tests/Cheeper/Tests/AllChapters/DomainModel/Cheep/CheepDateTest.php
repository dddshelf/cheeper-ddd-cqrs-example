<?php

declare(strict_types=1);

namespace Cheeper\Tests\AllChapters\DomainModel\Cheep;

use Cheeper\AllChapters\DomainModel\Cheep\CheepDate;
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
