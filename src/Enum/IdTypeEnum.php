<?php

namespace Observatby\Mir24Quiz\Enum;

use MyCLabs\Enum\Enum;
use Observatby\Mir24Quiz\Model\IntId;
use Observatby\Mir24Quiz\Model\Uuid;

class IdTypeEnum extends Enum
{
    private const AUTOINCREMENT_INTEGER = 'AUTOINCREMENT_INTEGER';
    private const BYNARY_UUID = 'BYNARY_UUID';

    public static function AUTOINCREMENT_INTEGER()
    {
        return new self(self::AUTOINCREMENT_INTEGER);
    }

    public static function BYNARY_UUID()
    {
        return new self(self::BYNARY_UUID);
    }

    public function getIdInterfaceClass(): string
    {
        $idInterfaces = [
            self::AUTOINCREMENT_INTEGER => IntId::class,
            self::BYNARY_UUID => Uuid::class
        ];
        return $idInterfaces[$this->getKey()];
    }
}
