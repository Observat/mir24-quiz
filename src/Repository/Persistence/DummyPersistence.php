<?php


namespace Observatby\Mir24Quiz\Repository\Persistence;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Repository\ListPersistenceInterface;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;


class DummyPersistence implements PersistenceInterface, ListPersistenceInterface
{
    public function persist(array $data): void
    {
        // dummy
    }

    public function retrieveList(): array
    {
        return [];
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
