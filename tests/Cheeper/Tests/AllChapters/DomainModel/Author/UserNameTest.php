<?php

declare(strict_types=1);

namespace Cheeper\Tests\AllChapters\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\UserName;
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
