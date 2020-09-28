<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Repository;


use Observatby\Mir24Quiz\Model\Id;


interface PersistenceInterface
{
    public function generateId(): Id;

    public function persist(array $data): void;

    public function retrieve(Id $id): array;

    public function delete(Id $id): void;
}
