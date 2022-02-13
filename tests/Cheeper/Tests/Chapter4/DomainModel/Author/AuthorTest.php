<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter4\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\BirthDate;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\DomainModel\Author\Website;
use Cheeper\Chapter4\DomainModel\Author\Author;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class AuthorTest extends TestCase
{
    /** @test */
    public function nameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Author::signUp(
            AuthorId::fromUuid(Uuid::uuid4()),
            UserName::pick('test'),
            new EmailAddress('test@email.com'),
            '',
            'test',
            'test',
            new Website('http://google.com'),
            new BirthDate((new DateTimeImmutable())->format('Y-m-d'))
        );
    }
}
