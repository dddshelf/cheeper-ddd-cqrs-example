<?php

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Faker\Factory as FakerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetFollowersCountControllerTest extends ApiTestCase
{
    use HelperFunctions;

    /** @test */
    public function itReturnsTheNumberOfFollowersOfAUser(): void
    {
        $client = self::createClient();

        // Register first author
        $fromAuthor = $this->createAuthorWithRandomizedData($client);

        // Register second author
        $toAuthor = $this->createAuthorWithRandomizedData($client);

        // Make first follow second author
        $this->makeFollow($client, $fromAuthor['id'], $toAuthor['id']);

        $totalFollowers = $this->getFollowersCount($client, $toAuthor['id']);

        $this->assertSame(1, $totalFollowers);
    }


}
