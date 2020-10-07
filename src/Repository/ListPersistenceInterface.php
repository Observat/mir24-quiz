<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Repository;


interface ListPersistenceInterface
{
    public function retrieveList(): array;
}
