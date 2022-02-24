<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Author\Projection;

use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjection;
use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjectionHandler;
use PHPUnit\Framework\TestCase;

final class CreateFollowersCounterProjectionHandlerTest extends TestCase
{
    /** @test */
    public function empytyRedisEntryWithAuthorIdAndUsernameAndZeroFollowers(): void
    {
        $authorId = '1c22ed61-c305-44dd-a558-f261f434f583';
        $authorUsername = 'alice';
        $redisKey = 'author_followers_counter_projection:'.$authorId;
        $redisRecord = [
            'id' => $authorId,
            'username' => $authorUsername,
            'followers' => 0,
        ];

        $redisMock = $this->createMock(\Redis::class);
        $redisMock
            ->expects($this->once())
            ->method('set')
            ->with($redisKey, json_encode($redisRecord));

        $handler = new CreateFollowersCounterProjectionHandler(
            $redisMock,
        );

        $handler(
            CreateFollowersCounterProjection::ofAuthor(
                $authorId,
                $authorUsername
            )
        );
    }
}
