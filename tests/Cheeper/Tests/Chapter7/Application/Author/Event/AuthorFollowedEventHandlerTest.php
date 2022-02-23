<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Author\Event;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\Chapter7\Application\Author\Event\AuthorFollowedEventHandler;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandlerInterface;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;
use Cheeper\Chapter7\DomainModel\Follow\Follow;
use PHPUnit\Framework\TestCase;

final class AuthorFollowedEventHandlerTest extends TestCase
{
    /**
     * @test
     * @Given Non Existing Customer Or Without
     * @When When
     * @Then
     */
    public function authorNonExistingOrWithoutFollowers(): void
    {
        $mock = $this->createMock(CountFollowersProjectionHandlerInterface::class);
        $mock->expects($this->once())->method('__invoke');

        $eventHandler = new AuthorFollowedEventHandler($mock);

        $follow = Follow::fromAuthorToAuthor(
            FollowId::nextIdentity(),
            AuthorId::nextIdentity(),
            AuthorId::nextIdentity(),
        );

        $eventHandler->handle(
            AuthorFollowed::fromFollow(
                $follow
            )
        );
    }
}
