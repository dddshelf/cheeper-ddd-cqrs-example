<?php

declare(strict_types=1);

namespace Cheeper\Tests\AllChapters\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\Website;
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
