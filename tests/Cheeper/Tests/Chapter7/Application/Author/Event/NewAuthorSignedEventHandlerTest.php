<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Author\Event;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter7\Application\Author\Event\NewAuthorSignedEventHandler;
use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjectionHandlerInterface;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;
use PHPUnit\Framework\TestCase;

final class NewAuthorSignedEventHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function delegateToTheCreateCountFollowersProjection(): void
    {
        $mock = $this->createMock(CreateFollowersCounterProjectionHandlerInterface::class);
        $mock->expects($this->once())->method('__invoke');

        $eventHandler = new NewAuthorSignedEventHandler($mock);

        $author = Author::signUp(
            AuthorId::nextIdentity(),
            UserName::pick('alice'),
            EmailAddress::from('alice@alice.com')
        );

        $eventHandler->handle(
            NewAuthorSigned::fromAuthor($author)
        );
    }
}
