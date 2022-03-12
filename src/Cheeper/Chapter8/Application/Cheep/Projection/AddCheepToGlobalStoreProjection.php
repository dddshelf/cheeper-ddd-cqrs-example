<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\Application\Cheep\Projection;

use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\Application\Projection;

final class AddCheepToGlobalStoreProjection implements Projection
{
    use MessageTrait;

    public function __construct(
        public string $authorId,
        public string $cheepId,
        public string $cheepMessage,
        public string $cheepDate,
    ) {
        $this->stampAsNewMessage();
    }
}
