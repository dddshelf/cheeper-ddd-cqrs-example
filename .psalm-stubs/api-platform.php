<?php

declare(strict_types=1);

namespace ApiPlatform\Core\DataPersister
{
    /** @template T */
    interface DataPersisterInterface
    {
        /**
         * @psalm-var mixed $data
         */
        public function supports($data): bool;

        /**
         * @psalm-var T $data
         * @psalm-return T|void
         */
        public function persist($data);

        /**
         * @psalm-var T $data
         * @psalm-return void
         */
        public function remove($data);
    }

    /**
     * @template T
     * @template-extends DataPersisterInterface<T>
     */
    interface ContextAwareDataPersisterInterface extends DataPersisterInterface { }
}
