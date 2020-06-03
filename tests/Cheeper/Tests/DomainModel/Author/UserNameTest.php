<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Author;

use Cheeper\DomainModel\Author\UserName;
use PHPUnit\Framework\TestCase;

final class UserNameTest extends TestCase
{
    /** @test */
    public function throwsAnExceptionIfEmptyUsernameIsGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        UserName::pick('');
    }
}
