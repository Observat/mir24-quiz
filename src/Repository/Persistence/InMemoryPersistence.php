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
        $this->inMemory[Id::fromDb($data['quiz']['id'])->toString()] = $data; # TODO Delete InMemoryPersistence?
    }

    public function retrieve(Id $id): array
    {
        if (!isset($this->inMemory[$id->toString()])) {
            throw new OutOfBoundsException(sprintf('No data found for ID %s', $id->toString()));
        }

        return $this->inMemory[$id->toString()];
    }

    public function delete(Id $id): void
    {
        if (!isset($this->inMemory[$id->toString()])) {
            throw new OutOfBoundsException(sprintf('No data found for ID %s', $id->toString()));
        }

        unset($this->inMemory[$id->toString()]);
    }
}
