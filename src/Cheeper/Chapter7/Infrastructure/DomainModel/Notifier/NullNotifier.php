<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\DomainModel\Notifier;

use Cheeper\Chapter7\DomainModel\Author\Author;

final class NullNotifier
{
    public function notify(Author $author): void
    {
        // Send email or do whatever
    }
}
