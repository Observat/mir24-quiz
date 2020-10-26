<?php


namespace Observatby\Mir24Quiz\UseCase\Traits;


use Observatby\Mir24Quiz\Enum\IdTypeEnum;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\QuizRepository;
use PDO;

trait CreateWithPdoTrait
{
    /**
     * @param PDO $pdo
     * @param IdTypeEnum $idTypeEnum
     * @return self
     * @throws QuizException
     */
    public static function createWithPdo(PDO $pdo, IdTypeEnum $idTypeEnum): self
    {
        return new self(new QuizRepository($idTypeEnum->getPersistence($pdo)));
    }
}
