<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class UuidResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return UuidInterface::class === $argument->getType();
    }

    /** @return iterable<UuidInterface> */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield Uuid::fromString(
            $request->attributes->getAlnum(
                $argument->getName()
            )
        );
    }
}
