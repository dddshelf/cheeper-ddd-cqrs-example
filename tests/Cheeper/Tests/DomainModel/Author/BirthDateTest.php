<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Author;

use Cheeper\DomainModel\Author\BirthDate;
use PHPUnit\Framework\TestCase;

final class BirthDateTest extends TestCase
{
    /** @test */
    public function throwsAnExceptionIfInvalidDateIsGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(0);

        new BirthDate('test');
    }
}
