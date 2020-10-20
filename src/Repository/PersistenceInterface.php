<?php declare(strict_types=1);

namespace Observatby\Mir24Quiz\Repository;


use Observatby\Mir24Quiz\IdInterface;
use Observatby\Mir24Quiz\QuizException;


interface PersistenceInterface
{
    /**
     * @param array $data
     * @throws QuizException
     */
    public function persist(array $data): void;

    public function retrieve(IdInterface $id): array;

    /**
     * @param IdInterface $id
     * @throws QuizException
     */
    public function delete(IdInterface $id): void;
}
