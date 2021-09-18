<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection;

final class CategoryId
{
    public static function create(): \Architecture\CQRS\Domain\CategoryId
    {
        return new \Architecture\CQRS\Domain\CategoryId('irrelevant');
    }
}
