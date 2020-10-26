<?php

namespace Observatby\Mir24Quiz\Enum;

use MyCLabs\Enum\Enum;
use Observatby\Mir24Quiz\IdInterface;
use Observatby\Mir24Quiz\Model\IntId;
use Observatby\Mir24Quiz\Model\Uuid;
use Observatby\Mir24Quiz\QuizException;
use Observatby\Mir24Quiz\Repository\ListPersistenceInterface;
use Observatby\Mir24Quiz\Repository\Persistence\QuizWithIncrementIdPersistence;
use Observatby\Mir24Quiz\Repository\Persistence\QuizWithUuidPersistence;
use Observatby\Mir24Quiz\Repository\PersistenceInterface;
use PDO;

class IdTypeEnum extends Enum
{
    private const AUTOINCREMENT_INTEGER = 'AUTOINCREMENT_INTEGER';
    private const BINARY_UUID = 'BINARY_UUID';

    public static function AUTOINCREMENT_INTEGER(): self
    {
        return new self(self::AUTOINCREMENT_INTEGER);
    }

    public static function BINARY_UUID(): self
    {
        return new self(self::BINARY_UUID);
    }

    /**
     * @return IdInterface
     * @throws QuizException
     */
    public function getIdInterface(): IdInterface
    {
        switch ($this->getKey()) {
            case self::AUTOINCREMENT_INTEGER:
                return IntId::createNew();
            case self::BINARY_UUID:
                return Uuid::createNew();
            default:
                throw new QuizException(QuizException::INCORRECT_ID_TYPE_ENUM);
        }
    }

    /**
     * @param PDO $pdo
     * @return PersistenceInterface
     * @throws QuizException
     */
    public function getPersistence(PDO $pdo): PersistenceInterface
    {
        switch ($this->getKey()) {
            case self::AUTOINCREMENT_INTEGER:
                return new QuizWithIncrementIdPersistence($pdo);
            case self::BINARY_UUID:
                return new QuizWithUuidPersistence($pdo);
            default:
                throw new QuizException(QuizException::INCORRECT_ID_TYPE_ENUM);
        }
    }

    /**
     * @param PDO $pdo
     * @return ListPersistenceInterface
     * @throws QuizException
     */
    public function getListPersistence(PDO $pdo): ListPersistenceInterface
    {
        switch ($this->getKey()) {
            case self::AUTOINCREMENT_INTEGER:
                return new QuizWithIncrementIdPersistence($pdo);
            case self::BINARY_UUID:
                return new QuizWithUuidPersistence($pdo);
            default:
                throw new QuizException(QuizException::INCORRECT_ID_TYPE_ENUM);
        }
    }
}
