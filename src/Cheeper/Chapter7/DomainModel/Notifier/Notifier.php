<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Notifier;

use Cheeper\Chapter7\DomainModel\Author\Author;

interface Notifier
{
    public function notify(Author $author): void;
}
