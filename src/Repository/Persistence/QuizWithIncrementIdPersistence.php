<?php

namespace Observatby\Mir24Quiz\Repository\Persistence;

use Observatby\Mir24Quiz\Repository\ListPersistenceInterface;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;

class QuizWithIncrementIdPersistence implements PersistenceInterface, ListPersistenceInterface, QuizQueryConstantsInterface
{
    use QuizPersistenceTrait;

    public function persist(array $data): void
    {
        die("TODO: Implement persist() method."); // TODO: Implement persist() method.
    }
}
