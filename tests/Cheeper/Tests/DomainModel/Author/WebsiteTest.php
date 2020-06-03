<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Author;

use Cheeper\DomainModel\Author\Website;
use PHPUnit\Framework\TestCase;

final class WebsiteTest extends TestCase
{
    /** @test */
    public function throwsExceptionWhenInvalidUrlIsGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Website('test');
    }
}
