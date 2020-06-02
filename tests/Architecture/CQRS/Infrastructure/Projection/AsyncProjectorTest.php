<?php

namespace Architecture\CQRS\Infrastructure\Projection;

use Architecture\CQRS\Domain\PostContentWasChanged;

use Architecture\CQRS\Domain\PostTitleWasChanged;


use Architecture\CQRS\Domain\PostWasCategorized;
use Architecture\CQRS\Domain\PostWasCreated;
use Architecture\CQRS\Domain\PostWasPublished;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostContentWasChangedProjection;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostTitleWasChangedProjection;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostWasCategorizedProjection;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostWasCreatedProjection;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostWasPublishedProjection;
use PHPUnit\Framework\TestCase;
use Spatie\Async\Pool;

class AsyncProjectorTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldProjectIntoElasticsearch(): void
    {
        $this->markTestSkipped('It\'s not stable. Sometimes fails and sometimes don\'t.');

        //Wait to kill the event receiver
        $pool = Pool::create()->timeout(3);

        $pool[] = async(function () {
            sleep(2);//wait for the connection to be ready

            //snippet event-receiver
            $client = \Elasticsearch\ClientBuilder::create()->build();

            $projector = new Projector();
            $projector->register([
                new PostWasCreatedProjection($client),
                new PostWasPublishedProjection($client),
                new PostWasCategorizedProjection($client),
                new PostTitleWasChangedProjection($client),
                new PostContentWasChangedProjection($client)
            ]);

            $serializer = new \Zumba\JsonSerializer\JsonSerializer();

            $bunny = (new \Bunny\Client())->connect();
            $channel = $bunny->channel();
            $channel->exchangeDeclare('events', 'fanout');
            $queue = $channel->queueDeclare('queue');
            $channel->queueBind($queue->queue, 'events');
            $channel->consume(
                function (
                    \Bunny\Message $message,
                    \Bunny\Channel $channel,
                    \Bunny\Client $client
                ) use ($serializer, $projector) {
                    $event = $serializer->unserialize($message->content);
                    $projector->project([$event]);
                },
                $queue->queue
            );
            $bunny->run();
            //end-snippet
        });

        $pool[] = async(function () {
            sleep(2);//wait for the receiver to be launched

            //snippet event-emitter
            $bunny = (new \Bunny\Client())->connect();
            $channel = $bunny->channel();
            $channel->exchangeDeclare('events', 'fanout');

            $serializer = new \Zumba\JsonSerializer\JsonSerializer();

            $postId = PostId::create();
            $categoryId = CategoryId::create();
            $projector = new AsyncProjector($channel, $serializer);
            $projector->project([
                new PostWasCreated($postId, 'A title', 'Some content'),
                new PostWasPublished($postId),
                new PostWasCategorized($postId, $categoryId),
                new PostTitleWasChanged($postId, 'New title'),
                new PostContentWasChanged($postId, 'New content'),
            ]);

            $channel->close();
            $bunny->disconnect();
            //end-snippet
        });

        await($pool);

        $client = \Elasticsearch\ClientBuilder::create()->build();

        $params = [
            'index' => 'posts',
            'type'  => 'post',
            'id'    => 'irrelevant'
        ];

        $document = $client->get($params);
        $this->assertEquals([
            'title' => 'New title',
            'content' => 'New content',
            'is_published' => true,
            'category_id' => 'irrelevant'
        ], $document['_source']);

        $client->delete($params);
        $client->indices()->delete(['index' => 'posts']);
    }
}
