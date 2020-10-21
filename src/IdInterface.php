<?php


namespace Observatby\Mir24Quiz;


interface IdInterface
{
    public static function createNew(): self;

    public static function fromDb(string $idFromDb): self;

    public static function fromString(string $idDisplayed): self;

    public function toDb();

    public function toString(): string;
}
