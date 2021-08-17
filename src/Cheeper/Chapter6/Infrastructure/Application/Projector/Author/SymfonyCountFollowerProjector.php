<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Projector\Author;

use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-projector-count-followers
final class SymfonyCountFollowerProjector implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowerProjector $appProjector
    ) { }

    public function __invoke(CountFollowers $query): void
    {
        $this->appProjector($query);
    }

    public static function getHandledMessages(): iterable
    {
        yield CountFollowers::class => [
            'bus' => 'event.bus'
        ];
    }
}
//end-snippet