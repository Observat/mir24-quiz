<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Repository;


use Observatby\Mir24Quiz\Model\Id;
use Observatby\Mir24Quiz\QuizException;


interface PersistenceInterface
{
    /**
     * @param array $data
     * @throws QuizException
     */
    public function persist(array $data): void;

    public function retrieve(Id $id): array;

    public function delete(Id $id): void;
}
