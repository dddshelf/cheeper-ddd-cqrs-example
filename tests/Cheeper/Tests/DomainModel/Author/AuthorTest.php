<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Author;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Safe\DateTimeImmutable;

final class AuthorTest extends TestCase
{
    /** @test */
    public function nameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Author::signUp(
            AuthorId::fromUuid(Uuid::uuid4()),
            UserName::pick('test'),
            '',
            'test',
            'test',
            new Website('http://google.com'),
            new BirthDate((new DateTimeImmutable())->format('Y-m-d'))
        );
    }
}
