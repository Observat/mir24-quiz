<?php


namespace Observatby\Mir24Quiz\Model;


use Observatby\Mir24Quiz\IdInterface;


class IntId implements IdInterface
{
    private ?int $id;

    private function __construct(?int $id)
    {
        $this->id = $id;
    }

    public static function createNew(): self
    {
        return new self(null);
    }

    public static function fromDb(string $idFromDb): self
    {
        return new self((int)$idFromDb);
    }

    public static function fromString(string $idDisplayed): self
    {
        return new self((int)$idDisplayed);
    }

    public function toDb(): ?int
    {
        return $this->id;
    }

    public function toString(): string
    {
        return (string)$this->id;
    }
}
