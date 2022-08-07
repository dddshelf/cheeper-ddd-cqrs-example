<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use function Functional\pluck;

final class GetTimelineControllerTest extends ApiTestCase
{
    use HelperFunctions;

    /** @test */
    public function itReturnsATimeline(): void
    {
        $client = self::createClient();

        // Register first author
        $firstAuthorData = $this->createAuthorWithRandomizedData($client);

        // Register second author
        $secondAuthorData = $this->createAuthorWithRandomizedData($client);

        // Register third author
        $thirdAuthorData = $this->createAuthorWithRandomizedData($client);

        // Register fourth author
        $fourthAuthorData = $this->createAuthorWithRandomizedData($client);

        // Register fourth author
        $fifthAuthorData = $this->createAuthorWithRandomizedData($client);

        // Make first follow everyone
        $this->makeFollow($client, $firstAuthorData['id'], $secondAuthorData['id']);
        $this->makeFollow($client, $firstAuthorData['id'], $thirdAuthorData['id']);
        $this->makeFollow($client, $firstAuthorData['id'], $fourthAuthorData['id']);

        // Make cheeps
        $cheep1 = $this->makeRandomizedCheep($client, $secondAuthorData['userName']);
        sleep(1);
        $cheep2 = $this->makeRandomizedCheep($client, $thirdAuthorData['userName']);
        sleep(1);
        $cheep3 = $this->makeRandomizedCheep($client, $fourthAuthorData['userName']);
        sleep(1);
        $this->makeRandomizedCheep($client, $fifthAuthorData['userName']);

        // Get timeline
        $timeline = $this->getAuthorTimeline($client, $firstAuthorData['id']);

        $this->assertIsArray($timeline);

        // Check that it only has the three cheeps that the author follows
        $this->assertCount(3, $timeline);
        $this->assertSame($cheep3['id'], $timeline[0]['id']);
        $this->assertSame($cheep2['id'], $timeline[1]['id']);
        $this->assertSame($cheep1['id'], $timeline[2]['id']);

        // Check that it does not have the cheep that the author does not follow
        $this->assertNotContains($fifthAuthorData['id'], pluck($timeline, 'authorId'));
    }
}