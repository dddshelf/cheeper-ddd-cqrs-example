<?php

declare(strict_types=1);

namespace Cheeper\Tests\AllChapters\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use PHPUnit\Framework\TestCase;

final class EmailAddressTest extends TestCase
{
    /** @test */
    public function throwsAnExceptionWhenInvalidEmailAddressIsGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new EmailAddress('test');
    }
}
