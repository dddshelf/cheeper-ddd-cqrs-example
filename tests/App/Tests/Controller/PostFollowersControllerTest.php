<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

final class PostFollowersControllerTest extends ApiTestCase
{
    use HelperFunctions;

    /** @test */
    public function itAllowsToFollowOtherAuthors(): void
    {
        $client = self::createClient();

        $firstAuthor = $this->createAuthorWithRandomizedData($client);
        $secondAuthor = $this->createAuthorWithRandomizedData($client);

        $this->makeFollow($client, $firstAuthor['id'], $secondAuthor['id']);

        sleep(3);

        $this->assertSame(0, $this->getFollowersCount($client, $firstAuthor['id']));
        $this->assertSame(1, $this->getFollowersCount($client, $secondAuthor['id']));
    }
}