<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Cheep\Event;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepDate;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\Chapter7\Application\Author\Event\AuthorFollowedEventHandler;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandler;
use Cheeper\Chapter7\Application\Cheep\Event\CheepPostedEventHandler;
use Cheeper\Chapter7\DomainModel\Cheep\CheepPosted;
use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Cheeper\Chapter7\DomainModel\Follow\FollowRepository;
use Cheeper\Chapter7\Infrastructure\Application\InMemoryProjectionBus;
use Cheeper\Chapter7\Infrastructure\DomainModel\Follow\InMemoryFollowRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CheepPostedEventHandlerTest extends TestCase
{
    /**
     * @test
     * @Given Non Existing Customer Or Without
     * @When When
     * @Then
     */
    public function authorNonExistingOrWithoutFollowers(): void
    {
        $emptyFollowersList = [];
        $followRepository = $this->createMock(FollowRepository::class);
        $followRepository->expects($this->once())->method('toAuthorId')->willReturn($emptyFollowersList);

        $projectionBus = new InMemoryProjectionBus();

        $eventHandler = new CheepPostedEventHandler(
            $followRepository,
            $projectionBus
        );

        $eventHandler->handle(
            CheepPosted::create(
                CheepId::nextIdentity(),
                AuthorId::nextIdentity(),
                CheepMessage::write('Hello World!'),
                new CheepDate(
                    (new DateTimeImmutable(
                        'now',
                        new \DateTimeZone('UTC')
                    ))->format('Y-m-d H:i:s')
                )
            )
        );

        $this->assertCount(0, $projectionBus->projections());
    }

    /**
     * @test
     * @Given Non Existing Customer Or Without
     * @When When
     * @Then
     */
    public function authorWithMultipleFollowers(): void
    {
        $rockStarAuthorId = AuthorId::nextIdentity();

        $followersList = [];
        for($i = 0; $i < 10; $i++) {
            $followersList[] = Follow::fromAuthorToAuthor(
                FollowId::nextIdentity(),
                AuthorId::nextIdentity(),
                $rockStarAuthorId
            );
        }

        $followRepository = $this->createMock(FollowRepository::class);
        $followRepository->expects($this->once())->method('toAuthorId')->willReturn($followersList);

        $projectionBus = new InMemoryProjectionBus();

        $eventHandler = new CheepPostedEventHandler(
            $followRepository,
            $projectionBus
        );

        $eventHandler->handle(
            CheepPosted::create(
                CheepId::nextIdentity(),
                $rockStarAuthorId,
                CheepMessage::write('Hello World!'),
                new CheepDate(
                    (new DateTimeImmutable(
                        'now',
                        new \DateTimeZone('UTC')
                    ))->format('Y-m-d H:i:s')
                )
            )
        );

        $projections = $projectionBus->projections();
        $this->assertCount(10, $projections);
    }
}
