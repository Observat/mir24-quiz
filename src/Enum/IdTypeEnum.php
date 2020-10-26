<?php

namespace Observatby\Mir24Quiz\Enum;

use MyCLabs\Enum\Enum;
use Observatby\Mir24Quiz\IdInterface;
use Observatby\Mir24Quiz\Model\IntId;
use Observatby\Mir24Quiz\Model\Uuid;
use Observatby\Mir24Quiz\QuizException;

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
}
