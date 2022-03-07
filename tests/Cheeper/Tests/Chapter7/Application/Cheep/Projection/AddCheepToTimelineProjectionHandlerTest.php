<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Cheep\Projection;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepDate;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\Chapter7\Application\Author\Event\AuthorFollowedEventHandler;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandler;
use Cheeper\Chapter7\Application\Cheep\Event\CheepPostedEventHandler;
use Cheeper\Chapter7\Application\Cheep\Projection\AddCheepToTimelineProjection;
use Cheeper\Chapter7\Application\Cheep\Projection\AddCheepToTimelineProjectionHandler;
use Cheeper\Chapter7\DomainModel\Cheep\CheepPosted;
use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Cheeper\Chapter7\DomainModel\Follow\FollowRepository;
use Cheeper\Chapter7\Infrastructure\Application\InMemoryProjectionBus;
use Cheeper\Chapter7\Infrastructure\DomainModel\Follow\InMemoryFollowRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class AddCheepToTimelineProjectionHandlerTest extends TestCase
{
    /**
     * @test
     * @Given Non Existing Customer Or Without
     * @When When
     * @Then
     */
    public function authorNonExistingOrWithoutFollowers(): void
    {
        $redisMock = $this->createMock(\Redis::class);
        $redisMock
            ->expects($this->once())
            ->method('lPush');

        $handler = new AddCheepToTimelineProjectionHandler($redisMock);
        $handler(new AddCheepToTimelineProjection(
                CheepId::nextIdentity()->toString(),
                AuthorId::nextIdentity()->toString(),
                CheepMessage::write('Hello World!')->message(),
                (new CheepDate(
                    (new DateTimeImmutable(
                        'now',
                        new \DateTimeZone('UTC')
                    ))->format('Y-m-d H:i:s')
                ))->date()
        ));
    }
}