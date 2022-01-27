<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Cheep\Projection;

use Cheeper\Chapter7\Application\Cheep\Projection\AddCheepToTimelineProjection;
use Cheeper\Chapter7\Application\Cheep\Projection\AddCheepToTimelineProjectionHandler;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet add-cheep-to-timeline-projection-handler
final class SymfonyAddCheepToTimelineProjectionHandler implements MessageSubscriberInterface
{
    public function __construct(
        private AddCheepToTimelineProjectionHandler $projector,
    ) {
    }

    public function handle(AddCheepToTimelineProjection $projection): void
    {
        $this->projector->__invoke($projection);
    }

    public static function getHandledMessages(): iterable
    {
        yield AddCheepToTimelineProjection::class => [
            'method' => 'handle',
            'from_transport' => 'chapter7_async_projections',
        ];
    }
}
//end-snippet
