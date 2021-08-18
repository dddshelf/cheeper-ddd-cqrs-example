<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Projector\Author;

use Cheeper\Chapter6\Application\Projector\Author\CountFollowerProjector;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-projector-count-followers
final class SymfonyCountFollowerProjector implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowerProjector $appProjector
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield CountFollowers::class => [
            'bus' => 'command.bus',
            'method' => 'handle'
        ];
    }

    public function handle(CountFollowers $projection): void
    {
        $this->appProjector->__invoke(
            CountFollowers::ofAuthor($projection->authorId())
        );
    }
}
//end-snippet
