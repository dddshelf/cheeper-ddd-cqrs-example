<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Author\Event;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\Application\Author\Event\AuthorFollowedEventHandler;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandler;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class AuthorFollowedEventHandlerTest extends TestCase
{

    protected function setUp(): void
    {

    }

    /** @test */
    public function itDelegatesIntoProjectionHandler(): void
    {
        $this->markTestSkipped();
    }
}
