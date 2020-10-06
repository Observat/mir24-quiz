<?php


namespace Observatby\Mir24Quiz\Repository\Persistence;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;
use OutOfBoundsException;


class InMemoryPersistence implements PersistenceInterface
{
    private array $inMemory = [];

    public function persist(array $data): void
    {
        $this->inMemory[$data['id']] = $data;
    }

    public function retrieve(Id $id): array
    {
        if (!isset($this->inMemory[$id->toDb()])) {
            throw new OutOfBoundsException(sprintf('No data found for ID %d', $id->toDb()));
        }

        return $this->inMemory[$id->toDb()];
    }

    public function delete(Id $id): void
    {
        if (!isset($this->inMemory[$id->toDb()])) {
            throw new OutOfBoundsException(sprintf('No data found for ID %d', $id->toDb()));
        }

        unset($this->inMemory[$id->toDb()]);
    }
}
