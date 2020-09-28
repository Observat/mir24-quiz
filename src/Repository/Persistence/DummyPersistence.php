<?php


namespace Observatby\Mir24Quiz\Repository\Persistence;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;


class DummyPersistence implements PersistenceInterface
{
    public function persist(array $data): void
    {
        // dummy
    }

    public function retrieve(Id $id): array
    {
        return [];
    }

    public function delete(Id $id): void
    {
        // dummy
    }
}
