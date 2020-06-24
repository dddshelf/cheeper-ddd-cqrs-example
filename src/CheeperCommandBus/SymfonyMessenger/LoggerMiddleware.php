<?php

declare(strict_types=1);

namespace CheeperCommandBus\SymfonyMessenger;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use function Safe\sprintf;

final class LoggerMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $commandClass = get_class($envelope->getMessage());

        $this->logger->info(sprintf('Executing command %s', $commandClass));

        try {
            $result = $stack->next()->handle($envelope, $stack);
            $this->logger->info(sprintf('Command %s executed successfully', $commandClass));
            return $result;
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('Command %s execution failed', $commandClass));
            throw $exception;
        }
    }
}
