<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Cheep\Projection;

use Cheeper\Chapter7\Application\Projection;

//snippet add-cheep-to-timeline-projection
final class AddCheepToTimelineProjection implements Projection
{
    public function __construct(
        public string $authorId,
        public string $cheepId,
        public string $cheepMessage,
        public string $cheepDate,
    ) {
    }
}
//end-snippet
