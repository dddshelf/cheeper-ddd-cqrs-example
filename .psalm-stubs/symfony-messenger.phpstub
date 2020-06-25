<?php

declare(strict_types=1);

namespace Symfony\Component\Messenger\Stamp
{
    /** @template T */
    final class HandledStamp
    {
        /** @psam-return T */
        public function getResult() { }
    }
}

namespace Symfony\Component\Messenger
{
    trait HandleTrait
    {
        /**
         * @template T
         * @psalm-param object|Envelope $message
         * @psalm-return T|Envelope
         */
        private function handle($message) { }
    }
}
