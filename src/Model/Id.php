<?php


namespace Observatby\Mir24Quiz\Model;


use Ramsey\Uuid\Codec\TimestampFirstCombCodec;
use Ramsey\Uuid\Generator\CombGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;


class Id
{
    private UuidInterface $id;


    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }


    public static function createNew(): self
    {
        return new self(self::createFactory()->uuid4());
    }

    public static function fromDb(string $idFromDb): self
    {
        return new self(Uuid::fromBytes($idFromDb));
    }

    public function toDb(): string
    {
        return $this->id->getBytes();
    }

    /**
     * @see https://uuid.ramsey.dev/en/latest/customize/timestamp-first-comb-codec.html#timestamp-first-comb-codec
     * @return UuidFactory
     */
    private static function createFactory(): UuidFactory
    {
        $factory = new UuidFactory();
        $codec = new TimestampFirstCombCodec($factory->getUuidBuilder());
        $factory->setCodec($codec);

        $factory->setRandomGenerator(new CombGenerator(
            $factory->getRandomGenerator(),
            $factory->getNumberConverter()
        ));

        return $factory;
    }
}
